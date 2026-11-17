import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';
import os from 'os';

/**
 * Helper to get the primary local LAN IPv4 address (e.g. 192.168.x.x)
 */
function getLocalIpAddress() {
  const interfaces = os.networkInterfaces();
  for (const name of Object.keys(interfaces)) {
    for (const iface of interfaces[name] || []) {
      if (iface.family === 'IPv4' && !iface.internal) {
        return iface.address;
      }
    }
  }
  return 'localhost';
}

/**
 * Plugin that writes storage/vite.hot when Vite dev server is running.
 * This lets backend/core/Vite.php detect local dev mode and automatically
 * serve scripts from Vite HMR across the local network without hardcoding URLs.
 */
function hotFilePlugin() {
  return {
    name: 'cho-hot-file',
    configureServer(server) {
      const storageDir = path.resolve(process.cwd(), 'storage');
      if (!fs.existsSync(storageDir)) {
        fs.mkdirSync(storageDir, { recursive: true });
      }
      server.httpServer?.once('listening', () => {
        const address = server.httpServer?.address();
        const port = typeof address === 'object' && address ? address.port : 5180;
        const hostIp = getLocalIpAddress();
        const hotUrl = `http://${hostIp}:${port}`;
        fs.writeFileSync(path.resolve(storageDir, 'vite.hot'), hotUrl, 'utf-8');
        console.log(`\n  [CHO Vite Bridge] Network hot-reload enabled at: ${hotUrl}\n`);
      });

      const cleanup = () => {
        const hotPath = path.resolve(storageDir, 'vite.hot');
        if (fs.existsSync(hotPath)) {
          try { fs.unlinkSync(hotPath); } catch (e) {}
        }
      };
      process.on('exit', cleanup);
      process.on('SIGINT', () => { cleanup(); process.exit(); });
      process.on('SIGTERM', () => { cleanup(); process.exit(); });
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
