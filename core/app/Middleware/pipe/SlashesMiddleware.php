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
class SlashesMiddleware implements MiddlewareInterface
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
        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] == "/") {

            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }
        return $handler->handle($request);
    }
}
