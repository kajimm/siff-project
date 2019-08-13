<?php
namespace Core\Middleware;

use Core\Middleware\pipe\DefaultHandler;
use Core\Middleware\pipe\MiddlewareHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Clase encargada de ejetutar los middlewares del sistema
 * Methodos que se registran en index-app.php
 */

class Middleware implements RequestHandlerInterface
{
    /**
     * @var MiddlewareInterface[]
     */
    private $midlewares = [];

    /**
     * @var RequestHandlerInterface
     */
    private $defaultHandler = null;

    /**
     * @param RequestHandlerInterface|null $defaultHandler
     */
    public function __construct(?RequestHandlerInterface $manejador = null)
    {
        if ($manejador === null) {
            $manejador = new DefaultHandler();
        }
        $this->defaultHandler = $manejador;
    }

    /**
     * [addMiddleware agregar nuevos middleware]
     * @param MiddlewareInterface $middleware [description]
     * @return  [void] [void]
     */
    public function addMiddleware(MiddlewareInterface $middleware) : void
    {
        $this->midlewares[] = $middleware;
    }

    /**
     * [setDefaultHandler define un manejador por defecto]
     * @param RequestHandlerInterface $defaultHandler [description]
     * @return  [void]
     */
    public function setDefaultHandler(RequestHandlerInterface $defaultHandler): void
    {
        $this->defaultHandler = $defaultHandler;
    }

    /**
     * [getDefaultHandler obtiene el manejador por defecto]
     * @return [RequestHandlerInterface] [description]
     */
    public function getDefaultHandler(): RequestHandlerInterface
    {
        return $this->defaultHandler;
    }

    /**
     * [handle ejecuta la llamada del manejador que se establecio o que lla esta establecido]
     * @param  ServerRequestInterface $request [description]
     * @return [ResponseInterface]                          [description]
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middle = $this->buildMiddleware();
        return $middle->handle($request);
    }

    /**
     * [buildMiddleware ejecuta la llamada http al manejador por defecto o el que se aya establecido]
     * @return [RequestHandlerInterface]
     */
    private function buildMiddleware(): RequestHandlerInterface
    {
        $default = $this->defaultHandler;
        foreach (array_reverse($this->midlewares) as $middleware) {
            $default = new MiddlewareHandler($middleware, $default);
        }
        return $default;
    }
}
