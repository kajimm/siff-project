<?php
namespace Core\Middleware\pipe;

use Zend\Diactoros\Response\RedirectResponse;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware que comprueba el tiempo de inactividad de un usuario
 */
class InactivityMiddleware implements MiddlewareInterface
{
    /**
     * [$session SessionInterface]
     * @var [SessionInterface]
     */
    private $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if (in_array($request->getMethod(), ['GET', 'POST', 'PUT', 'DELETE'])) {
            $sesion = !empty($this->session->get('user')) ? $this->session->get('user') : null;
            if (!is_null($sesion)) {
                if ($sesion['time'] < time()) {
                    $this->session->delete('user');
                    $this->session->destroy();
                    return new RedirectResponse('/acceso/login');
                } else {
                    $sesion['time'] = time() + 1800;
                    return $handler->handle($request);
                }
            } else {
                return $handler->handle($request);
            }
        }
    }
}
