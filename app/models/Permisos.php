<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $table = "permisos";

    protected $fillable = [
    	'modulo',
    	'descripcion'
    ];
}
