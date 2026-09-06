<?php

/**
 * Post-deployment webhook.
 *
 * Called by GitHub Actions after FTP upload with a Bearer token that must
 * match DEPLOY_HOOK_SECRET in .env. Bootstraps Laravel just enough to run
 * a fixed list of artisan commands (migrate, cache rebuild, storage link).
 *
 * DO NOT delete or rename this file: the deploy workflow depends on it.
 */

require __DIR__ . '/../vendor/autoload.php';

// Load .env manually since Laravel isn't fully bootstrapped yet.
try {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    exit('env load failed: ' . $e->getMessage());
}

$expected = $_ENV['DEPLOY_HOOK_SECRET'] ?? getenv('DEPLOY_HOOK_SECRET') ?? '';
if ($expected === '') {
    http_response_code(500);
    header('Content-Type: text/plain');
    exit('DEPLOY_HOOK_SECRET is not set in .env on the server');
}

$header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$provided = str_starts_with($header, 'Bearer ') ? substr($header, 7) : '';

if (! hash_equals($expected, $provided)) {
    http_response_code(403);
    header('Content-Type: text/plain');
    exit('forbidden');
}

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$commands = [
    'optimize:clear' => [],
    'storage:link'   => [],
    'migrate'        => ['--force' => true],
    'config:cache'   => [],
    'route:cache'    => [],
    'view:cache'     => [],
];

$results = [];
foreach ($commands as $name => $params) {
    try {
        $status = $kernel->call($name, $params);
        $results[$name] = [
            'status' => $status,
            'output' => trim(Illuminate\Support\Facades\Artisan::output()),
        ];
    } catch (Throwable $e) {
        $results[$name] = [
            'status' => -1,
            'output' => 'EXCEPTION: ' . $e->getMessage(),
        ];
    }
}

header('Content-Type: application/json');
echo json_encode([
    'deployed_at' => date('c'),
    'commands' => $results,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
