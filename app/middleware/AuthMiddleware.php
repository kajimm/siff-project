<?php
namespace App\middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use GuzzleHttp\Psr7\Response;
/**
 * 
 */
class AuthMiddleware implements MiddlewareInterface
{
	
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
       var_dump($request->getParsedBody());
       exit();
    }
}