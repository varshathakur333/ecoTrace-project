<?php

// Clean up REQUEST_URI to prevent Vercel prefix 404 routing issues
if (isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = str_replace('/ecoTrace/api/index.php', '', $_SERVER['REQUEST_URI']);
    $_SERVER['REQUEST_URI'] = str_replace('/api/index.php', '', $_SERVER['REQUEST_URI']);
    if (empty($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = '/';
    }
}

// 1. Prepare writeable folders in /tmp for Vercel serverless environment
$tmpDirs = ['/tmp/views', '/tmp/sessions', '/tmp/cache'];
foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Set environment variables for writeable paths
putenv('VIEW_COMPILED_PATH=/tmp/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';

putenv('SESSION_DRIVER=cookie');
$_ENV['SESSION_DRIVER'] = 'cookie';

putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';

// 2. Initialize dynamic SQLite database in /tmp
$dbPath = '/tmp/database.sqlite';
$isNewDb = !file_exists($dbPath);
if ($isNewDb) {
    @touch($dbPath);
}

putenv("DB_CONNECTION=sqlite");
$_ENV['DB_CONNECTION'] = 'sqlite';
putenv("DB_DATABASE={$dbPath}");
$_ENV['DB_DATABASE'] = $dbPath;

// Forward Vercel request to Laravel public/index.php entrypoint
require __DIR__ . '/../public/index.php';

// 3. Programmatically run migrations and seeders on first boot
if ($isNewDb && isset($app)) {
    try {
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('migrate:fresh', ['--force' => true]);
        $kernel->call('db:seed', ['--force' => true]);
    } catch (\Exception $e) {
        error_log('Vercel SQLite Initialization failed: ' . $e->getMessage());
    }
}
