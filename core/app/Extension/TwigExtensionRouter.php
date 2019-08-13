<?php
namespace Core\Extension;

use \League\Route\RouteCollectionInterface;

/**
 * Description
 *
 * Clase Route_Twig_Extension
 * retorna la ruta seleccionada por nombre
 * @example home => /home
 * Luego se adjunta a twig para poder usarla dentro de las plantillas
 * @example uso {{ Path('nombre de la ruta') }} => $route->map('GET', '/home', function(){})->setName('home')
 * @method Route_Twig_Extension
 * @author freyder rey <freyder@siff.com.co>
 */
class TwigExtensionRouter extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * Description
     * @param RouteCollectionInterface $router
     * @return Router
     */
    public function __construct(RouteCollectionInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Description
     * Obtiene y establece la funcion al nombre que se le asigno
     * @return Object
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('router', [$this, 'path']),
        ];
    }
    /**
     * Description
     * Obtiene el path de la ruta
     * @param string $name
     * @return string
     */
    public function path(string $name): string
    {
        $name = $this->router->getNamedRoute($name);
        $path = $name->getPath();
        $uri = str_replace('{id}', '', $path);
        return $uri;
    }
}
