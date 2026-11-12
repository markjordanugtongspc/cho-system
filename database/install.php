<?php
/**
 * CLI installer: creates the database, tables and the first admin account.
 *
 * Usage (Laragon Terminal, from the project root):
 *     php database/install.php
 *
 * Reads DB_* and SEED_ADMIN_* values from .env.
 * If SEED_ADMIN_PASSWORD is empty you will be prompted for one.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $parts = explode('\\', substr($class, 4));
    $file  = array_pop($parts);
    $path  = BASE_PATH . '/backend/' . implode('/', array_map('strtolower', $parts)) . '/' . $file . '.php';
    if (is_file($path)) {
        require $path;
    }
});
require BASE_PATH . '/backend/helpers.php';
App\Core\Env::load(BASE_PATH . '/.env');

$out = static fn (string $msg) => fwrite(STDOUT, $msg . PHP_EOL);
$c   = config('database');
$db  = $c['database'];

if (!preg_match('/^[A-Za-z0-9_]+$/', $db)) {
    $out('✖ DB_DATABASE may only contain letters, numbers and underscores.');
    exit(1);
}

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $c['host'], $c['port']),
        $c['username'],
        $c['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => true
        ]
    );
} catch (PDOException $e) {
    $out('✖ Cannot connect to MySQL. Is Laragon "Start All" running? ' . $e->getMessage());
    exit(1);
}

// 1) Schema — swap the default DB name for the one in .env, then execute
$sql = (string) file_get_contents(__DIR__ . '/schema.sql');
$sql = str_replace('`cho_marawi`', '`' . $db . '`', $sql);
$pdo->exec($sql);
$out("✔ Database `{$db}` and tables are ready.");

// 2) Admin account
$username = (string) env('SEED_ADMIN_USERNAME', 'admin');
$password = (string) env('SEED_ADMIN_PASSWORD', '');
$first    = (string) env('SEED_ADMIN_FIRST_NAME', 'System');
$last     = (string) env('SEED_ADMIN_LAST_NAME', 'Administrator');

$exists = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$exists->execute([$username]);
if ($exists->fetch()) {
    $out("ℹ Admin user \"{$username}\" already exists — skipped.");
    exit(0);
}

if ($password === '') {
    fwrite(STDOUT, "Enter a password for \"{$username}\" (min " . config('security.password.min_length') . ' chars): ');
    $password = trim((string) fgets(STDIN));
}

$min = (int) config('security.password.min_length');
if (strlen($password) < $min || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
    $out("✖ Password must be at least {$min} characters and include upper-case, lower-case and a number.");
    exit(1);
}

$roleId = (int) $pdo->query("SELECT id FROM roles WHERE slug = 'admin'")->fetchColumn();
$stmt   = $pdo->prepare(
    'INSERT INTO users (username, password_hash, first_name, last_name, role_id, status, password_changed_at)
     VALUES (?, ?, ?, ?, ?, \'active\', NOW())'
);
$stmt->execute([$username, App\Models\User::hashPassword($password), $first, $last, $roleId]);

$out("✔ Admin user \"{$username}\" created.");
$out('  → Remove SEED_ADMIN_PASSWORD from .env now that the account exists.');
