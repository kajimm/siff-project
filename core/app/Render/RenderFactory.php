<?php

namespace Core\Render;

use Psr\Container\ContainerInterface;

/**
 * Description
 *
 * Clase que hace parte de php-di
 * Funciona como fabrica para retorno de objetos dentro del contenedor
 * En esta clase esta la configuracion en general de las plantillas
 * @example debug, cahe, autoscape
 * Para agregar una nueva extencion a twig, el metodo se integra en el archivo config/ConfigContainer.php
 * luego de averse creado en core/app/Extension
 * En el apartado de
 *              Twig_Extension [
 *                  aqui va la extencion ver formato de las direcciones
 *              ]
 *
 * @method Factory
 * @link http://php-di.org/doc/php-definitions.html#values
 * @author freyder rey <freyder@siff.com.co>
 * @return Object Renderer
 */
class RenderFactory
{
    /**
     * Description
     * Funcion que invoca la creacion del objeto Twig
     * Se utliza las definiciones dentro del archivo de configuracion del contenedor
     * @param ContainerInterface $container
     * @return Renderer class
     */
    public function __invoke(ContainerInterface $container): Renderer
    {
        $debug = $container->get('env') === 'production';
        $views  = $container->get('views_path');
        $loader = new \Twig\Loader\FilesystemLoader($views);

        $twig = new \Twig\Environment($loader, [
            'cache'            => $debug ? 'app/cache/twig': false,
            'debug'            => !$debug,
            'auto_reload'      => $debug,
            'charset'          => 'utf-8',
            'strict_variables' => true,
            'autoescape'       => false,
            'optimizations'    => 0,
        ]);

        if ($container->has('Twig_Extension')) {
            foreach ($container->get('Twig_Extension') as $extension) {
                $twig->addExtension($extension);
            }
        }
        return new Renderer($loader, $twig);
    }
}
