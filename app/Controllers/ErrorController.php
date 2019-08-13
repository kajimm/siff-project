<?php
namespace App\Controllers;

use Core\Render\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Core\Controller\IController;

class ErrorController
{
    const DEFINITIONS = null;
    private $render;

    public function __construct(RendererInterface $renderer)
    {
        $this->render = $renderer;
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
    	$response = new Response();
        $response->getBody()->write($this->render->render('errors/401', array('code' => $response->getStatusCode())));
        return $response->withStatus($response->getStatusCode());
        
    }
}
