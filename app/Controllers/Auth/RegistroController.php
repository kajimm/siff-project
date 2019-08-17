<?php
namespace App\Controllers\Auth;

use Core\Render\RendererInterface as Render;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Core\Controller\Controller;
use \Core\session\FlashMessage;
use GuzzleHttp\Psr7\Response;
use App\modules\Auth\authenticate\AccessInterface;

/**
 *
 */
class RegistroController extends Controller {
	private $render;
	private $flash;
  private $auth;

	public function __construct(Render $renderer, FlashMessage $flash, AccessInterface $auth) {
		$this->render = $renderer;
		$this->flash = $flash;
    $this->auth = $auth;
	}

	public function index(Request $request): ResponseInterface{

		$template = $this->render->render('ingreso/registro', array());
		return $this->response($template, 200);
	}

	public function register(Request $request): ResponseInterface{

		$datos = $request->getParsedBody();
    $registro = $this->auth->register($datos);

    if(is_string($registro)){
      $this->flash->error($registro);
      return new Response(200, ['location' => '/acceso/registro'], '');
    }else if($registro === true){
      $this->flash->success('Registro exitoso ¿Vamos a probar que tu cuenta este habilitada?');
      return new Response(200, ['location' => '/acceso/login'], '');
    }else{
      $this->flash->warning('Este usuario ya existe intenta nuevamente');
      return new Response(200, ['location' => '/acceso/registro'], '');
    }
	}
}
