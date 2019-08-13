<?php
namespace App\Controllers;

use Core\Render\RendererInterface as Render;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\models\Usuarios;
use \Core\Controller\Controller;

/**
 *
 */
class UsersController extends Controller
{
    private $render;

    public function __construct(Render $renderer)
    {
        $this->render = $renderer;
    }

    public function index(Request $request): Response
    {
    
        $template = $this->render->render('usuarios', array());

        return $this->response($template, 200);
    }

    public function users(Request $request): Response{
        $users = Usuarios::all();
        return $this->response($users, 200);
    }

}
