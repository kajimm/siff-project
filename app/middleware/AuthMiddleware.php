<?php declare (strict_types = 1);

namespace App\middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use \Core\session\FlashMessage;
use \Core\session\Session;

class AuthMiddleware implements MiddlewareInterface
{
    private $session;
    private $flash;

    public function __construct()
    {
        $this->session = new Session();
        $this->flash   = new FlashMessage($this->session);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['POST', 'GET', 'PUT', 'DELETE'])) {
            if ($this->session->get('user')) {
                return $handler->handle($request);
            } else {
                $this->flash->warning('Inicia session para ingresar a la pagina principal');
                return new RedirectResponse('/acceso/login', 401);
            }
        }
    }
}
