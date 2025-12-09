<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

<<<<<<< HEAD
// Determine if the application is in maintenance mode...
=======
// Tentukan apakah aplikasi dalam mode maintenance...
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

<<<<<<< HEAD
// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
=======
// Daftarkan Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel dan tangani request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture()); // BARIS INI KEMUNGKINAN BESAR ADALAH BARIS 20
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
