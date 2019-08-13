<?php
namespace Core\Middleware\pipe;

use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * [$keyform identificador que se debe enviar por los formularios]
     * @var [string]
     */
    private $keyform;

    /**
     * [$session clase session]
     * @var [SessionInterface]
     */
    private $session;

    /**
     * [$sessionKey identificador de session clave para recuperar los valores a comparar]
     * @var [type]
     */
    private $sessionKey;

    private $limit_tokens;

    public function __construct($session, string $keyform = '_csrf', string $sessionKey = 'csrf')
    {
        $this->session    = $session;
        $this->keyform    = $keyform;
        $this->sessionKey = $sessionKey;
        $this->limit_tokens = 50;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $param = $request->getParsedBody() ?: [];
            if (!array_key_exists($this->keyform, $param)) {
                $this->error();
            } else {
                $list = $this->session->get($this->sessionKey) ?? [];
                if (in_array($param[$this->keyform], $list)) {
                    $this->token($param[$this->keyform]);
                    $this->session->delete($this->sessionKey);
                    return $handler->handle($request);
                } else {
                    $this->error();
                }
            }
        } else {
            return $handler->handle($request);
        }
    }

    /**
     * [generateToken nuevo token]
     * @return [string] [token]
     */
    public function generateToken(): string
    {
        $token  = bin2hex(random_bytes(32));
        $csrf   = $this->session->get($this->sessionKey) ?? [];
        $csrf[] = $token;
        $this->session->addToken($this->sessionKey, $csrf);
        $this->limitTokens();
        return $token;
    }

    /**
     * [error throw new Exception]
     * @return [void] [Exeption]
     */
    private function error(): void
    {
        throw new \Exception("Hay que redirecciona en caso de estar mal el token", 1);
    }

    /**
     * [token compara los tokens y en caso de que no coincida retorna el token invalido ]
     * @param  [token] $token [token a comparar]
     * @return [void]        [void]
     */
    private function token($token): void
    {
        $list = array_filter($this->session->get($this->sessionKey), function ($key) use ($token) {
            return $token !== $key;
        });
        $tokens = $this->session->get($this->sessionKey);
        $tokens = $list;
    }

    /**
     * [limitTokens limita la creacion de n uevos token]
     * @return [void]
     */
    private function limitTokens(): void{
        $tok = $this->session->get($this->sessionKey) ?? [];
        if(count($tok) > $this->limit_tokens){
            array_shift($tok);
        }
        $listTokens = $this->session->get($this->sessionKey);
        $listTokens = $tok;
    }

    /**
     * [getkeyform obtiene el identificador de csrf]
     * @return [void]
     */
    public function getkeyform(): string{
        return $this->keyform;
    }

}
