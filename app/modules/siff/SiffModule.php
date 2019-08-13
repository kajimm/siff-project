<?php
namespace App\modules\siff;

use Core\Module\Module;
use \Core\Mail\MailerInterface;
use \League\Route\RouteCollectionInterface;
use \League\Route\RouteGroup;

class SiffModule extends Module
{
    const DEFINITIONS = __DIR__ .'/./ConfigSiff.php';
    const MIGRATIONS  = __DIR__ . "/../../../database/migration";
    const SEEDS       = __DIR__ . "/../../../database/seeds";

    /**
     * [__construct description]
     * @param string                   $prefijo [Prefijo del modulo]
     * @param RouteCollectionInterface $router  [Definir rutas]
     * @param MailerInterface          $mailer  [Mailer envio de correo electronicos]
     */
    public function __construct(string $modulo, RouteCollectionInterface $router, MailerInterface $mailer)
    {
        $router->group('/admin', function(RouteGroup $route){

            $route->map('GET', '/home', \App\Controllers\HomeController::class)->setName('home');
            $route->map('GET', '/usuarios', ['\App\Controllers\UsersController', 'index'])->setName('usuarios');
            $route->map('GET', '/usuarios/get', ['\App\Controllers\UsersController', 'users']);
            
        
        })->middleware(new \App\middleware\AuthMiddleware);
    }
}
