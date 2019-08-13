<?php
namespace App\Controllers;

use Core\Render\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Core\Controller\IController;
use \App\models\Usuarios;


class HomeController 
{
    const DEFINITIONS = null;
    private $render;
    private $cryp;

    public function __construct(RendererInterface $renderer)
    {
        $this->render = $renderer;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->index());
        return $response->withStatus(200);
    }

    public function index()
    {
        $count = Usuarios::count();
        return $this->render->render('home', compact('count'));
    }
}
