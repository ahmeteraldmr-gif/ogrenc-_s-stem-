<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/**
 * cPanel deployment support:
 * - In local/dev environments, Laravel lives at "../" (project root).
 * - On cPanel, we often place the full Laravel app at "../laravel_app" and keep
 *   only the public files in the web root.
 */
$laravelBasePath = realpath(__DIR__.'/..');
if (is_dir(__DIR__.'/../laravel_app')) {
    $laravelBasePath = realpath(__DIR__.'/../laravel_app') ?: $laravelBasePath;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelBasePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelBasePath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $laravelBasePath.'/bootstrap/app.php';

// Make Vite/asset paths resolve to the actual web root (e.g. /public_html).
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
