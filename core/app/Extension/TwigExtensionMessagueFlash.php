<?php
namespace Core\Extension;

use Core\session\FlashMessage;

/**
 *Extension twig que obtiene el mensaje que se establece en cada caso de uso
 */
class TwigExtensionMessagueFlash extends \Twig_Extension
{
    /**
     * [$flash description]
     * @var [FlashMessage  class]
     */
    private $flash;

    /**
     * [__construct description]
     * @param FlashMessage $flashMessage [description]
     */
    public function __construct(FlashMessage $flashMessage)
    {
        $this->flash = $flashMessage;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('flash', [$this, 'getFlash']),
        ];
    }

    /**
     * [getFlash description]
     * Retorna el emnsaje que se establecio para los mensajes flash
     * @param  string $type [description]
     * @return [string OR null]       [description]
     */
    public function getFlash(string $type):  ? string
    {
        return $this->flash->getMessague($type);
    }
}
