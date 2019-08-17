<?php
namespace App\modules\Auth\authenticate;

interface AccessInterface 
{
	public function login(array $data);
}