<?php
namespace App\Controllers\Auth;

use Core\Render\RendererInterface as Render;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Core\Controller\Controller;
use \Core\session\FlashMessage;
use App\modules\Auth\authenticate\AccessInterface;
use \Core\Session\SessionInterface as Session;
use Core\Capcha\Capcha;

/**
 *Controlador Inicio de sesion
 */
class LoginController extends Controller {

	private $render;
	private $flash;
    private $auth;
    private $session;
    private $capcha;

	/**
	 * [__construct description]
	 * @param RendererInterface $renderer [Twig templates]
	 * @param LogInterface      $log      [Registro de logs del sistema]
	 * @param FlashMessage      $flash    [Mensajes flash]
	 */
	public function __construct(Render $renderer, FlashMessage $flash, AccessInterface $auth, Session $session, Capcha $capcha) {
		$this->render = $renderer;
        $this->auth = $auth;
        $this->flash = $flash;
        $this->session = $session;
        $this->capcha = $capcha;
	}

    /**
     * [index]
     * Render de aplicación
     * @param  Request $request [peticion http]
     * @return [type]           [ResponseInterface]
     */
	public function index(Request $request): ResponseInterface{
        $key = $this->session->get('intentos');
		$response = new Response();
		$response->getBody()->write($this->render->render('ingreso/login', array("intentos" => $key)));
		return $response->withStatus(200);
	}

    /**
     * [run]
     * Iniciar session
     * @param  Request $request [peticion http]
     * @return [ResponseInterface]          
     */
	public function run(Request $request): ResponseInterface{

		$data = $request->getParsedBody();

        if(array_key_exists('g-recaptcha-response', $data)){
            
            $resultado = $this->capcha->check($data['g-recaptcha-response']);

            if(!$resultado){
                $this->flash->error('El capcha no se pudo validar intenta nuevamente');
                return new Response(200, ['Location' => '/acceso/login'], '');
            }
            $this->session->delete('intentos');
        }

        /**
         *        ||
         *       \  /
         */

        $login = $this->auth->login($data);

        if(is_string($login)){
            $this->flash->error($login);
            return new Response(200, ['Location' => '/acceso/login'], '');
        }else if($login == true){

            $this->session->delete('intentos');
            $this->flash->success('Session iniciada Bienvenido (a)');
            return new Response(200, ['Location' => '/admin/home'], '');

        }else{

            $this->flash->error('Credenciales incorectas');
            $this->session->attemps('intentos', 1);
            return new Response(200, ['Location' => '/acceso/login'], '');

        }
		
	}
}
