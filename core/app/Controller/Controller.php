<?php
namespace Core\Controller;

use Core\Render\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Core\Middleware\Middleware;

/**
 * Clase modelo de los controladores
 * @author  freyder rey <freyder@siff.com.co>
 * @package  siff-project
 * @copyright copyright todos los derechos reservados
 * @license MIT 
 */
class Controller
{
    public function response(string $mensaje, int $status, array $header = []) : ResponseInterface
    {
        return new Response($status, $header, $mensaje);
    }


    public function middleware(Request $request, ...$middle): ResponseInterface
    {  
    	$midleware = new Middleware();

        foreach ($middle as $key) {
            $midleware->addMiddleware($key);
        }

        $req = $midleware->handle($request);

        return $req;
    }
}