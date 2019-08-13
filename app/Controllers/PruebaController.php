<?php
namespace App\Controllers;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \App\models\Usuarios;

class PruebaController
{
    const DEFINITIONS = null;

    public function index(ServerRequestInterface $request): ResponseInterface
    {	
    	
        Usuarios::create([
            'nombre'   => "test",
            'apellido' => "drive",
            'correo'   => "tes@test.com",
            'password' => "1234fkdjkdkjdk",
            'celular'  => "31245678",
            'id_role'  => 2,
        ]);
        

        $response = new Response();
        $response->getBody()->write("Listo");
        return $response->withStatus(200);
    }
}
