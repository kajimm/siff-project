<?php declare (strict_types = 1);
namespace App\middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use \Core\session\Session;

/**
 * App\middleware\AccessMiddleware
 */
class AccessMiddleware implements MiddlewareInterface
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['POST', 'GET', 'PUT', 'DELETE'])) {
            if ($this->session->get('user')) {
                return new RedirectResponse('/admin/home', 401);
            } else {
                return $handler->handle($request);
            }
        }
    }
}
