<?php
namespace Core\Middleware\pipe;

use GuzzleHttp\Psr7\Response;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

class VerifySessionMiddleware implements MiddlewareInterface
{

    /**
     * [$session clase session]
     * @var [SessionInterface]
     */
    private $session;

    private $key;

    public function __construct($session, string $key)
    {
        $this->session = $session;
        $this->key = $key;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if(PHP_SAPI === "cli"){
            return $handler->handle($request);
        }else{
            $clave = hash('sha256', $_SERVER['HTTP_USER_AGENT']);
        if (!empty($this->session->get('user'))) {
            $user = $this->session->get('user');
            if ($user['id'] === $clave) {
                return $handler->handle($request);
            } else {
                return new Response(401, [], "<h1>Unauthorized</h1><br><h4>Lo que intentas hacer esta muy mal</h4>");
            }
        } else {
            return $handler->handle($request);
        }
        }
    }
}
