<?php
chdir(dirname(__DIR__));
date_default_timezone_set('America/Bogota');
require_once "vendor/autoload.php";

use \App\modules\Auth\AuthModule;
use \App\modules\siff\SiffModule;
use \Core\App;
use \Core\DB\Conection;
use \Core\Exception\ExceptionHandler;
use \Core\Middleware\pipe\CsrfMiddleware;
use \Core\Middleware\pipe\HttpMiddleware;
use \Core\Middleware\pipe\InactivityMiddleware;
use \Core\Middleware\pipe\RouterMiddleware;
use \Core\Middleware\pipe\SlashesMiddleware;
use \Core\Middleware\pipe\VerifySessionMiddleware;




/**
 * Conection uso del ORM eloquent
 */
new Conection();

/**
 * Manejo de errores
 */
new ExceptionHandler();

$app = (new App('config/ConfigContainer.php'))
    ->addModule(SiffModule::class)
    ->addModule(AuthModule::class)
    ->pipe(SlashesMiddleware::class)
    ->pipe(HttpMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(VerifySessionMiddleware::class)
    ->pipe(InactivityMiddleware::class)
    ->pipe(RouterMiddleware::class);

$response = $app->run(GuzzleHttp\Psr7\ServerRequest::fromGlobals());

\Http\Response\send($response);
