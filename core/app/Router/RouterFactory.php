<?php
namespace Core\Router;

use Psr\Container\ContainerInterface;
use \Core\Strategies\StrategySiff;
use \Zend\Diactoros\ResponseFactory;
use \League\Route\Router;
use \League\Route\Strategy\ApplicationStrategy;

/**
 * Description
 * Clase hace parte de PHP-DI
 * Se instancia la clase Router y se pasa al contenedor PHP-DI
 * @return Factory fabrica del metodo
 * @author freyder rey <freyder@siff.com.co>
 */
class RouterFactory
{

    /**
     * Description
     * Metodo magico encargado de instanciar la clase router
     * A la clase router se le pasa una estrategia perzonalizada
     * Se le agrega el container con la finalidad de inyectar las clases que pudieran ser necesarias
     * @example En los controladores es necesario usa el render de plantillas
     * Mediante el contenedor le pasamos el RendererInterface
     * @param ContainerInterface $container
     * @return void:
     */
    public function __invoke(ContainerInterface $container)
    {
        $responseFactory = new ResponseFactory;
        $Strategy_custom = new ApplicationStrategy($responseFactory);
        $strategy        = ($Strategy_custom)->setContainer($container);
        $router          = (new Router)->setStrategy($strategy);

        return $router;
    }
}
