<?php
namespace Core\Extension;

use Core\session\SessionInterface;

class TwigExtensionSession extends \Twig_Extension
{
    /**
     * [$session description]
     * @var [SessionInterface]
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('name', [$this, 'getName']),
        ];
    }
    /**
     * Description
     * Funcion que obtiene el nombre del usuario
     * @return Se define con el proposito que lo crees
     *
     */
    public function getName()
    {
        if($this->session->get('user') !== null){
            return $this->session->get('user')['nombre'];
        }
        return null;
    }

}
