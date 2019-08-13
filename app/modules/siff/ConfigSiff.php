<?php
use \App\modules\siff\SiffModule;

return [
    'prefijo'         => '/admin',
    SiffModule::class => \DI\autowire()->constructorParameter('modulo', \DI\get('prefijo')),
];
