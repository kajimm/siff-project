<?php

namespace Core\Render;

/**
 * Interface clase Renderer
 * Util para determinar la estructura de la clase
 * e inyectar dependencias
 */
interface RendererInterface
{

    /**
     * Description
     * Agregar la ruta predeterminada de las plantillas en el sistema
     * @param string $namespace
     * @param ?|null $path
     * @return void
     */
    public function addPath(string $namespace, string $path = null) : void;

    /**
     * Description
     * Compila y renderiza la plantilla solicitada
     * @param string $view
     * @param array|array $params
     * @return string
     */

    public function render(string $view, array $params = []): string;

    /**
     * Description
     * Variables globales detro de las plantillas
     * @param string $key
     * @param type $value
     * @return void
     */

    public function addGlobal(string $key, string $value): void;
}
