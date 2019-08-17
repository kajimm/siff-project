<?php
namespace App\Controllers\Auth;

use Core\Render\RendererInterface as Render;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Core\Controller\Controller;
use \Core\Log\LogInterface;
use \Core\session\FlashMessage;
use \Core\Validate\ValidateInterface as Valid;
use \App\models\Usuarios;
use Zend\Diactoros\Response\RedirectResponse;


/**
 *
 */
class RegistroController extends Controller
{
    private $render;
    private $log;
    private $flash;
    private $validar;

    public function __construct(Render $renderer, LogInterface $log, FlashMessage $flash, Valid $validar)
    {
        $this->render = $renderer;
        $this->log    = $log;
        $this->flash = $flash;
        $this->validar = $validar;
        
    }

    public function index(Request $request): Response
    {

        $template = $this->render->render('ingreso/registro', array());
        return $this->response($template, 200);
    }

    public function register(Request $request) : Response
    {
        $datos = $request->getParsedBody();



        $this->validar->params($datos)
                      ->required('nombre', 'apellido', 'correo', 'telefono', 'password', 'tyc')
                      ->minLength('nombre', 5)
                      ->minLength('apellido', 5)
                      ->minLength('telefono', 8)
                      ->minLength('password', 8)
                      ->maxLength('nombre', 50)
                      ->maxLength('apellido', 50)
                      ->maxLength('telefono', 11)
                      ->noEmpty('nombre', 'apellido', 'correo', 'telefono', 'password')
                      ->email('correo')
                      ->number('telefono')
                      ->error();

        if(is_string($this->validar->valid())){
            $this->flash->error($this->validar->valid());
            return new RedirectResponse('/acceso/registro', 200);
        }else{
            $registro = Usuarios::register($datos);

            if($registro){
                $this->log->Looguer(
                    'NOTICE',
                    'Registro',
                    'Usuario registrado exitosamente',
                    array('Usuario' => $datos['nombre'])
                );
                $this->flash->success('Registro exitoso ¿Vamos a probar que tu cuenta este habilitada?');
                return new RedirectResponse('/acceso/login', 200);
            }else{
                $this->flash->error('Las credenciales que estas suministrando son incorrectas');
                return new RedirectResponse('/acceso/registro', 200);
            }
        }
    }
}
