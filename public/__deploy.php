<?php

/**
 * Post-deployment webhook.
 *
 * Called by GitHub Actions after FTP upload with a Bearer token that must
 * match DEPLOY_HOOK_SECRET in .env. Bootstraps Laravel just enough to run
 * a fixed list of artisan commands (migrate, cache rebuild, storage link).
 *
 * Errors are always reported (JSON body) even in production, so a failed
 * deploy hook is diagnosable from CI logs.
 *
 * DO NOT delete or rename this file: the deploy workflow depends on it.
 */

// Force error reporting so a fatal doesn't just yield an empty 500.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json');

function respond(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

$appRoot = dirname(__DIR__);

// 1. Sanity: autoload exists.
$autoload = $appRoot . '/vendor/autoload.php';
if (! is_file($autoload)) {
    respond(500, [
        'error' => 'vendor/autoload.php not found',
        'expected_path' => $autoload,
        'app_root' => $appRoot,
    ]);
}

try {
    require $autoload;
} catch (Throwable $e) {
    respond(500, [
        'error' => 'autoload failed',
        'message' => $e->getMessage(),
    ]);
}

// 2. Load .env manually.
try {
    Dotenv\Dotenv::createImmutable($appRoot)->safeLoad();
} catch (Throwable $e) {
    respond(500, [
        'error' => 'dotenv failed',
        'message' => $e->getMessage(),
    ]);
}

// 3. Check the secret.
$expected = $_ENV['DEPLOY_HOOK_SECRET'] ?? getenv('DEPLOY_HOOK_SECRET') ?? '';
if ($expected === '') {
    respond(500, ['error' => 'DEPLOY_HOOK_SECRET is not set in .env']);
}

$header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$provided = str_starts_with($header, 'Bearer ') ? substr($header, 7) : '';

if (! hash_equals($expected, $provided)) {
    respond(403, ['error' => 'forbidden']);
}

// 4. Bootstrap Laravel.
try {
    $app = require_once $appRoot . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
} catch (Throwable $e) {
    respond(500, [
        'error' => 'laravel bootstrap failed',
        'message' => $e->getMessage(),
        'file' => $e->getFile() . ':' . $e->getLine(),
        'trace_head' => array_slice(explode("\n", $e->getTraceAsString()), 0, 5),
    ]);
}

// 5. Run the artisan commands one by one, capturing per-command failures.
$commands = [
    'optimize:clear' => [],
    'storage:link'   => [],
    'migrate'        => ['--force' => true],
    'config:cache'   => [],
    'route:cache'    => [],
    'view:cache'     => [],
];

$results = [];
$overallOk = true;
foreach ($commands as $name => $params) {
    try {
        $status = $kernel->call($name, $params);
        $results[$name] = [
            'status' => $status,
            'output' => trim(Illuminate\Support\Facades\Artisan::output()),
        ];
        if ($status !== 0) {
            $overallOk = false;
        }
    } catch (Throwable $e) {
        $overallOk = false;
        $results[$name] = [
            'status' => -1,
            'output' => 'EXCEPTION: ' . $e->getMessage(),
            'file'   => $e->getFile() . ':' . $e->getLine(),
        ];
    }
}

respond($overallOk ? 200 : 500, [
    'deployed_at' => date('c'),
    'commands' => $results,
]);
