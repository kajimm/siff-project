<?php

namespace Core\Render;

use \Core\Render\RendererInterface;

/**
 *Description
 * Clase encargada de renderizar las plantilla twig en el sistema
 * @example $this->render('home', $params('title' => 'home'))
 * @link Documentacion twig https://twig.symfony.com/
 * @method Renderer
 * @author freyder rey <freyder@siff.com.co>
 */
class Renderer implements RendererInterface
{
    /**
     * @var \Twig\Loader\FilesystemLoader
     */
    private $loader;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * Description
     * Esta clase se factoriza para integrar al contenedor PHP-DI
     * Revisar archivo core/app/Render/RenderFactory.php
     * @param \Twig\Loader\FilesystemLoader $loader
     * @param \Twig\Environment $twig
     * @return Object
     */
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig)
    {

        $this->loader = $loader;
        $this->twig   = $twig;
    }

    /**
     * Description
     * Establece una ruta
     * @example en los modulos se establece para renderizar las plantillas de una carpeta exclusiva para ese modulo
     * @param string $namespace
     * @param ?|null string $path
     * @return void
     */
    public function addPath(string $namespace, string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * Description
     * Se encarga de renderizar las plantillas resources/views
     * @param string $view
     * @param array|array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.twig', $params);
    }

    /**
     * Description
     * Metodo encargado de agregar extenciones a twig para usar dentro de las plantillas
     * @example core/app/Extension  en esta carpeta se definen las extenciones que se deseen agregar a twig
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addGlobal(string $key, string $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
