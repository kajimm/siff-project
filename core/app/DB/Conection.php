<?php
namespace Core\DB;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Clase encargada de conectar con la base de datos
 * Usando Illuminate Database
 */
class Conection
{
    private $capsule;

    public function __construct()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'SIFF_PROJECT',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }
}
