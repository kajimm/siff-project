<?php
namespace App\Controllers\Auth;

use Core\Render\RendererInterface as Render;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Core\Controller\Controller;
use \Core\session\FlashMessage;
use App\modules\Auth\authenticate\AccessInterface;

/**
 *Controlador Inicio de sesion
 */
class LoginController extends Controller {

	private $render;
	private $flash;
    private $auth;

	/**
	 * [__construct description]
	 * @param RendererInterface $renderer [Twig templates]
	 * @param LogInterface      $log      [Registro de logs del sistema]
	 * @param FlashMessage      $flash    [Mensajes flash]
	 */
	public function __construct(Render $renderer, FlashMessage $flash, AccessInterface $auth) {
		$this->render = $renderer;
        $this->auth = $auth;
        $this->flash = $flash;
	}

	public function index(Request $request): ResponseInterface{
		$response = new Response();
		$response->getBody()->write($this->render->render('ingreso/login', array()));
		return $response->withStatus(200);
	}

	public function run(Request $request): ResponseInterface{

		$data = $request->getParsedBody();
        $login = $this->auth->login($data);

        if(is_string($login)){
            $this->flash->error($login);
            return new Response(200, ['Location' => '/acceso/login'], '');
        }else if($login == true){
            $this->flash->success('Session iniciada Bienvenido (a)');
            return new Response(200, ['Location' => '/admin/home'], '');
        }else{
            $this->flash->error('Credenciales incorectas');
            return new Response(200, ['Location' => '/acceso/login'], '');
        }
		
	}
}
