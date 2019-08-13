<?php

namespace Core\Module;

/**
 *Description
 * Clase que define las constantes para el manejo de las migraciones
 * @method Module
 * @author freyder rey <freyder@siff.com.co>
 * @param DEFINITIONS archivo de configuracion de los modulos
 * @param MIGRATIONS migraciones por ejecutar
 * @param SEEDS para ejecutar
 * @return path de archivos
 */
abstract class Module
{
    const DEFINITIONS = null;
    const MIGRATIONS  = null;
    const SEEDS       = null;
}
