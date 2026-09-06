<?php

/**
 * Post-upload vendor extractor.
 *
 * Called by the deploy workflow AFTER lftp uploads vendor.zip to the app
 * root. Extracts vendor.zip into ../vendor/ then deletes the zip.
 *
 * Standalone (no Composer autoload dependency) so it can bootstrap the
 * vendor directory itself before __deploy.php needs it.
 *
 * Guarded by DEPLOY_HOOK_SECRET (same secret as __deploy.php).
 */

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

// Manual .env parse (no Dotenv lib available — it's inside vendor).
$appRoot = dirname(__DIR__);
$envFile = $appRoot . '/.env';
$expected = null;
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), 'DEPLOY_HOOK_SECRET=')) {
            $expected = trim(substr(trim($line), strlen('DEPLOY_HOOK_SECRET=')), " \"'");
            break;
        }
    }
}

if (! $expected) {
    respond(500, ['error' => 'DEPLOY_HOOK_SECRET not found in .env']);
}

$header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$provided = str_starts_with($header, 'Bearer ') ? substr($header, 7) : '';
if (! hash_equals($expected, $provided)) {
    respond(403, ['error' => 'forbidden']);
}

$zipPath = $appRoot . '/vendor.zip';
if (! is_file($zipPath)) {
    respond(500, [
        'error' => 'vendor.zip not found',
        'expected_path' => $zipPath,
    ]);
}

$zipSize = filesize($zipPath);

// Extract using ZipArchive (built into most PHP installs).
if (! class_exists('ZipArchive')) {
    respond(500, ['error' => 'ZipArchive class not available (php-zip extension missing)']);
}

$zip = new ZipArchive();
$openResult = $zip->open($zipPath);
if ($openResult !== true) {
    respond(500, [
        'error' => 'failed to open vendor.zip',
        'zip_error_code' => $openResult,
    ]);
}

$fileCount = $zip->numFiles;

// Nuke any existing vendor/ before extraction so we start clean.
// (rmdir_recursive)
$vendorDir = $appRoot . '/vendor';
if (is_dir($vendorDir)) {
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($vendorDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $entry) {
        $entry->isDir() ? rmdir($entry->getRealPath()) : unlink($entry->getRealPath());
    }
    rmdir($vendorDir);
}

// Extract.
$extractOk = $zip->extractTo($appRoot);
$zip->close();

if (! $extractOk) {
    respond(500, ['error' => 'zip extractTo returned false']);
}

// Verify vendor/autoload.php now exists and has some size.
$autoload = $appRoot . '/vendor/autoload.php';
if (! is_file($autoload) || filesize($autoload) < 100) {
    respond(500, [
        'error' => 'extraction succeeded but vendor/autoload.php is missing or empty',
        'autoload_exists' => is_file($autoload),
        'autoload_size' => is_file($autoload) ? filesize($autoload) : 0,
    ]);
}

// Cleanup the zip.
@unlink($zipPath);

respond(200, [
    'extracted_at' => date('c'),
    'zip_size_bytes' => $zipSize,
    'files_in_zip' => $fileCount,
    'autoload_size' => filesize($autoload),
]);
