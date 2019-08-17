<?php
namespace App\Controllers\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\modules\Auth\authenticate\AccessInterface;
use GuzzleHttp\Psr7\Response;
/**
 *
 */
class LogoutController
{
    private $auth;
    private $flash;
   
   function __construct(AccessInterface $auth)
   {
       $this->auth = $auth;
   }
    /**
     * [__invoke description]
     * Cerrar session
     * @param  ServerRequestInterface $request [description]
     * @return [ResponseInterface]                          [description]
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if($this->auth->logout()){
            return new Response(200, ['Location' => '/acceso/login']);
        }else{
            return new Response(200, ['Location' => '/acceso/login']);
        }
    }
}
