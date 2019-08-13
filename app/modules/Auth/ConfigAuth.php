<?php
use \App\modules\Auth\AuthModule;

return [
    'prefijo'         => '/acceso',
    AuthModule::class => \DI\autowire()->constructorParameter('prefijo', \DI\get('prefijo')),
];
