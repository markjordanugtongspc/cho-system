import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';
import os from 'os';

/**
 * Helper to get the primary local LAN IPv4 address (e.g. 192.168.x.x),
 * skipping virtual/VPN adapters (like 26.x.x.x) and falling back to localhost.
 */
function getLocalIpAddress() {
  const interfaces = os.networkInterfaces();
  const candidates = [];
  for (const name of Object.keys(interfaces)) {
    for (const iface of interfaces[name] || []) {
      if (iface.family === 'IPv4' && !iface.internal) {
        candidates.push(iface.address);
      }
    }
  }
  const lanIp = candidates.find((ip) =>
    ip.startsWith('192.168.') || ip.startsWith('10.') || /^172\.(1[6-9]|2\d|3[0-1])\./.test(ip)
  );
  return lanIp || 'localhost';
}

/**
 * Plugin that writes storage/vite.hot when Vite dev server is running.
 * This lets backend/core/Vite.php detect local dev mode and automatically
 * serve scripts from Vite HMR on localhost or across the local network.
 */
function hotFilePlugin() {
  return {
    name: 'cho-hot-file',
    configureServer(server) {
      const storageDir = path.resolve(process.cwd(), 'storage');
      if (!fs.existsSync(storageDir)) {
        fs.mkdirSync(storageDir, { recursive: true });
      }
      const hotPath = path.resolve(storageDir, 'vite.hot');

      server.httpServer?.once('listening', () => {
        const address = server.httpServer?.address();
        const port = typeof address === 'object' && address ? address.port : 5180;
        const hostIp = getLocalIpAddress();
        const hotUrl = `http://${hostIp}:${port}`;
        fs.writeFileSync(hotPath, hotUrl, 'utf-8');
        console.log(`\n  [CHO Vite Bridge] Hot-reload enabled at: http://localhost:${port} (LAN: ${hotUrl})\n`);
      });

      const cleanup = () => {
        if (fs.existsSync(hotPath)) {
          try { fs.unlinkSync(hotPath); } catch (e) {}
        }
      };
      server.httpServer?.on('close', cleanup);
      process.on('exit', cleanup);
      process.on('SIGINT', () => { cleanup(); process.exit(); });
      process.on('SIGTERM', () => { cleanup(); process.exit(); });
    }
  };
}

/**
 * Plugin that triggers a full browser reload whenever any PHP view/layout file
 * is saved during `npm run dev`.
 */
function phpFullReloadPlugin() {
  return {
    name: 'cho-php-full-reload',
    configureServer(server) {
      const rootDir = process.cwd();
      const watchDirs = [
        path.resolve(rootDir, 'frontend'),
        path.resolve(rootDir, 'backend')
      ];
      server.watcher.add(watchDirs);

      let reloadTimer = null;
      const triggerReload = (file) => {
        if (!file.endsWith('.php')) return;
        clearTimeout(reloadTimer);
        reloadTimer = setTimeout(() => {
          server.ws.send({ type: 'full-reload', path: '*' });
        }, 60);
      };

      server.watcher.on('change', triggerReload);
      server.watcher.on('add', triggerReload);
    }
  };
}

/**
 * Plugin that copies static asset directories (icons, images, sounds) from
 * frontend/assets/ into dist/assets/ during production builds (`npm run build`).
 */
function copyStaticAssetsPlugin() {
  return {
    name: 'cho-copy-static-assets',
    closeBundle() {
      const rootDir = import.meta.dirname;
      const outAssetsDir = path.resolve(rootDir, 'dist', 'assets');
      const folders = ['icons', 'images', 'sounds'];

      for (const folder of folders) {
        const src = path.resolve(rootDir, 'frontend', 'assets', folder);
        const dest = path.resolve(outAssetsDir, folder);
        if (fs.existsSync(src)) {
          fs.mkdirSync(path.dirname(dest), { recursive: true });
          fs.cpSync(src, dest, { recursive: true });
          console.log(`  [CHO Build] Copied frontend/assets/${folder} -> dist/assets/${folder}`);
        }
      }
    }
  };
}

export default defineConfig({
  plugins: [
    tailwindcss(),
    hotFilePlugin(),
    phpFullReloadPlugin(),
    copyStaticAssetsPlugin()
  ],
  server: {
    host: true, // enables --host mode so it binds to 0.0.0.0/network
    port: 5180, // distinct custom port avoiding 5173
    strictPort: false,
    cors: true,
    watch: {
      ignored: ['**/frontend/assets/images/**', '**/storage/**', '**/vendor/**']
    }
  },
  build: {
    outDir: 'dist',
    manifest: true,
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(import.meta.dirname, 'frontend/assets/js/main.js')
      }
    }
  }
});
