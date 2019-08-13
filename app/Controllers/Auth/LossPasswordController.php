<?php
namespace App\Controllers\Auth;

use Core\Render\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Core\Log\LogInterface;

/**
 *
 */
class LossPasswordController
{
    private $render;
    private $log;
    public function __construct(RendererInterface $renderer, LogInterface $log)
    {
        $this->render = $renderer;
        $this->log    = $log;
    }

    public function start(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->render->render('ingreso/losspass', array()));
        return $response->withStatus(200);
    }

    public function recover()
    {
        # code...
    }
}
