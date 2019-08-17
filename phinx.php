<?php
/**
 * Description
 * Se integra el index de la aplicacion para obtener los modulos que solicitan migraciones
 *  y asi mismo crearlas en las rutas que proporcionan
 * las rutas se establecen en las constantes MIGRATIONS y SEEDS de los modulos
 * @method Configuracion de phinx para ejecutar las migraciones
 * @author freyder rey <freyder@siff.com.co>
 * @return void
 * */

require 'bootstrap/index-app.php';

$migration = [];
$seeds     = [];
foreach ($app->getModules() as $module) {
    if ($module::MIGRATIONS) {
        $migration[] = $module::MIGRATIONS;
    }

    if ($module::SEEDS) {
        $seeds[] = $module::SEEDS;
    }
}

return [
    'paths'        => [
        'migrations' => $migration,
        'seeds'      => $seeds,
    ],
    'environments' => [
        'default_database' => 'development',
        'development'      => [
            'adapter' => 'mysql',
            'host'    => 'localhost',
            'name'    => 'SIFF_PROJECT',
            'user'    => 'root',
            'pass'    => 'root',
            'port'    => '3306',
            'charset' => 'utf8',
        ],
    ],
];
