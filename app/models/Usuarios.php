<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "usuarios";
    protected $dateFormat = 'Y-m-d H:i:s';

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
}
