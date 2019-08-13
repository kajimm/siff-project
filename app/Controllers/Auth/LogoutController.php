<?php
namespace App\Controllers\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use \Core\Log\LogInterface;
use \Core\session\SessionInterface;

/**
 *
 */
class LogoutController
{
    /**
     * [$log description]
     * @var [LogInterface]
     */
    private $log;
    private $session;

    public function __construct(LogInterface $log, SessionInterface $session)
    {
        $this->log     = $log;
        $this->session = $session;
    }
    /**
     * [__invoke description]
     * Cerrar session
     * @param  ServerRequestInterface $request [description]
     * @return [ResponseInterface]                          [description]
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->session->get('user')) {
            $this->log->newLog('NOTICE');
            $this->log->rewriteChanel('sessiones');
            $this->log->registrar(
                'Session finalizada:: METODO:: {' . $request->getMethod() . '}{ URL:: ' . $request->getUri() . '}{ PROTOCOLO:: ' . $request->getProtocolVersion() . ' }',
                array('Usuario' => $this->session->get('user')['nombre']));
        }
        $this->session->delCookie();
        $this->session->destroy();
        return new RedirectResponse('/acceso/login', 200);
    }
}
