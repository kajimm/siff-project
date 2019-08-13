<?php

namespace Core\Middleware\pipe;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \League\Route\RouteCollectionInterface;

/**
 * Middleware encargado del enrutamiento
 */
class RouterMiddleware implements MiddlewareInterface
{

    /**
     * [$router recibe el enrutador]
     * @var [RouteCollectionInterface]
     */
    private $router;

    /**
     * [__construct description]
     * Enrutador
     * @param RouteCollectionInterface $route [description]
     */
    public function __construct(RouteCollectionInterface $route)
    {
        $this->router = $route;
    }

    /**
     * [process description]
     * Proccesa las solicitudes http y enruta
     * @param  ServerRequestInterface  $request [estandar MiddlewareInterface]
     * @param  RequestHandlerInterface $handler [estandar MiddlewareInterface]
     * @return [ResponseInterface]              [estandar MiddlewareInterface]
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->router->dispatch($request);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            return $handler->handle($request);
        }
    }
}
