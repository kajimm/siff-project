<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "usuarios";

    protected $fillable = [
        'id_usuario,',
        'nombre',
        'apellido',
        'correo',
        'password',
        'token_acount',
        'telefono',
        'created_at',
        'updated_at',
        'id_role',
    ];

    public static function login(array $data)
    {
        //$filter  = "/^[a-zA-Z0-9]*$/";
        $usuario = self::where('correo', "=", $data['username'])
            ->where('password', "=", $data['password'])
            ->get();
        if (sizeof($usuario) > 0) {
            return $usuario;
        } else {
            return null;
        }

    }

    public function register(array $datos)
    {

        $validar = self::where('correo', "=", $datos['correo'])->get();

        if(sizeof($validar) > 0)
        {
            return null;
        }else{
            self::create($datos);
            return true;
        }
    }

    public function lostPassword()
    {

    }
}
