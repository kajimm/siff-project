<?php
namespace App\Controllers\Auth;

use Core\Render\RendererInterface as Render;
use Core\session\SessionInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use \Core\Log\LogInterface;
use \Core\session\FlashMessage;
use \Core\Validate\ValidateInterface;
use \App\models\Usuarios;
use \Core\Controller\Controller;
use \Core\Middleware\Middleware;


/**
 *Controlador Inicio de sesion
 */
class LoginController extends Controller
{
    private $render;
    private $log;
    private $flash;
    private $session;
    private $validar;

    /**
     * [__construct description]
     * @param RendererInterface $renderer [Twig templates]
     * @param LogInterface      $log      [Registro de logs del sistema]
     * @param FlashMessage      $flash    [Mensajes flash]
     */
    public function __construct(Render $renderer, LogInterface $log, SessionInterface $session, FlashMessage $flash, ValidateInterface $validar)
    {
        $this->render  = $renderer;
        $this->log     = $log;
        $this->session = $session;
        $this->flash   = $flash;
        $this->validar = $validar;
    }

    public function start(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->render->render('ingreso/login', array()));
        return $response->withStatus(200);
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {     
        
        $this->middleware($request, new \App\middleware\AuthMiddleware);
        
        exit();




        $data = $request->getParsedBody();

        $this->validar->params($data)
            ->required('username', 'password')
            ->minLength('username', 10)
            ->minLength('password', 8)
            ->maxLength('username', 30)
            ->maxLength('password', 64)
            ->error();

        if (is_string($this->validar->valid())) {
            $this->flash->error($this->validar->valid());
            return new RedirectResponse('/acceso/login');
        } else {
            $validacion = Usuarios::login($data);
        
            if (null !== $validacion) {
                $this->log->Looguer(
                    'INFO',
                    'Session',
                    'Session iniciada',
                    array('usuario' => $validacion[0]->nombre)
                );

                $this->session->set('user', $validacion);
                $this->flash->success('Session iniciada Bienvenido (a)');
                return new RedirectResponse('/admin/home');
            } else {
                $this->flash->error('Credenciales incorrectas');
                return new RedirectResponse('/acceso/login');
            }
            
        }
    }
}
