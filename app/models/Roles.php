<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "roles";

    protected $fillable = [
    	'nombre',
    	'descripcion'
    ];
}
