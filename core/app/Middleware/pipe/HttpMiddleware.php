<?php

namespace Core\Middleware\pipe;

use \GuzzleHttp\Psr7\Response;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

/**
 *Clase que maneja la barra inclinada sola
 * retorna 301 Moved Permanently
 * revisar clase GuzzleHttp\Psr7\Response
 */
class HttpMiddleware implements MiddlewareInterface
{
    /**
     * [process description]
     * Processa las peticiones http
     * @param  ServerRequestInterface  $request [estandar MiddlewareInterface]
     * @param  RequestHandlerInterface $handler [estandar MiddlewareInterface]
     * @return [ResponseInterface]                           [estandar MiddlewareInterface]
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $method = $request->getParsedBody();
        if (array_key_exists('_method', $method) && in_array($method['_method'], ['POST', 'PUT'])) {
            $request = $request->withMethod($method['_method']);
            return $request;
        }
        return $handler->handle($request);
    }
}
