<?php
namespace Core\Exception;

/**
 * Clase que utiliza el manejador de errores de whoops
 * se encapsula en esta clase para organizacion del codigo
 */
class ExceptionHandler
{
    /**
     * [$whoops description]
     * @var [Whoops]
     */
    private $whoops;

    public function __construct()
    {
        $this->whoops = new \Whoops\Run;
        $this->whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $this->whoops->register();
    }
}
