<?php
namespace App\modules\Auth;

use Core\Module\Module;
use \Core\Mail\MailerInterface;
use \League\Route\RouteCollectionInterface;
use \League\Route\RouteGroup;

class AuthModule extends Module
{
    const DEFINITIONS = __DIR__ . '/ConfigAuth.php';
    const MIGRATIONS  = __DIR__ . "/db/migrations";
    const SEEDS       = __DIR__ . "/db/seeds";

    /**
     * [__construct description]
     * @param string                   $prefijo [Prefijo del modulo]
     * @param RouteCollectionInterface $router  [Definir rutas]
     * @param MailerInterface          $mailer  [Mailer envio de correo electronicos]
     */
    public function __construct(string $prefijo, RouteCollectionInterface $router, MailerInterface $mailer)
    {



        $router->group($prefijo, function (RouteGroup $route) {

            //Login
            $route->map('GET', '/login', ['\App\Controllers\Auth\LoginController', 'index'])
                ->setName('login');
            $route->map('POST', '/iniciar', ['\App\Controllers\Auth\LoginController', 'run'])
                  ->setName('iniciar');

            //registro
            $route->map('GET', '/registro', ['\App\Controllers\Auth\RegistroController', 'index'])
                ->setName('registro');
            $route->map('POST', '/register', ['\App\Controllers\Auth\RegistroController', 'register'])
                  ->setName('register');

            //recuperar contraseña
            $route->map('GET', '/lossPass', ['\App\Controllers\Auth\LossPasswordController', 'start'])
                ->setName('lossPass');         
            $route->map('POST', '/recover', ['\App\Controllers\Auth\LossPasswordController', 'recover'])
                  ->setName('recover');

        })->middleware(new \App\middleware\AccessMiddleware);;

        $router->post($prefijo . '/logout', \App\Controllers\Auth\LogoutController::class)
            ->setName('logout');
    }
}
