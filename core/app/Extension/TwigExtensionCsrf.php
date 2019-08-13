<?php
namespace Core\Extension;

use Core\Middleware\pipe\CsrfMiddleware;

/**
 * Core\Extension\TwigExtensionCsrf
 */
class TwigExtensionCsrf extends \Twig_Extension
{
    /**
     * [$csrf CsrfMiddleware]
     * @var [string]
     */
    private $csrf;

    public function __construct(CsrfMiddleware $csrf)
    {
        $this->csrf = $csrf;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf', [$this, 'token']),
        ];
    }

    /**
     * [token genera los token dentro de la session para ser comparados dentro del middleware]
     * @return [string] [cadena con el token generado]
     */
    public function token(): string
    {
        $template = '<input type="hidden" value="' . $this->csrf->generateToken() . '" name="'.$this->csrf->getkeyform().'"/>';
        return $template;
    }
}
