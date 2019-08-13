<?php
namespace Core\Extension;

/**
 * Core\Extension\TwigExtensionPrueba
 * Description
 * Clase que extiende de Twig_Extension para generar funciones para usar dentro de las plantillas
 * @method getFunctions() @return void new Twig_SinpleFunction @param String metodo array [Object, metodo]
 * @method Function user @return void
 * @method Twig_Extension
 * @author Freyder rey <freyder@siff.com.co>
 * @return void
 */
class TwigExtensionAssets extends \Twig_Extension
{

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('assets', [$this, 'assets']),
        ];
    }
    /**
     * Description
     * Esta funcion es la que se ejecutara dentro de las plantillas
     * @return Se define con el proposito que lo crees
     *
     */
    public function assets(string $file): string
    {
        $ruta = 'http://localhost:8080/assets/'.$file;
        return $ruta;
    }
}
