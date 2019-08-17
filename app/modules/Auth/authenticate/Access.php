<?php
namespace App\modules\Auth\authenticate;

use \App\models\Usuarios;

/**
 * 
 */
class Access implements AccessInterface
{

	private $session;
	private $log;
	private $valid;
	private $cryp;
	
	public function __construct($session, $log, $valid, $cryp)
	{
		$this->session = $session;
		$this->log = $log;
		$this->valid = $valid;
		$this->cryp = $cryp;	
	}

	public function login(array $data)
	{
		$this->valid->params($data)
			->required('username', 'password')
			->minLength('username', 10)
			->minLength('password', 8)
			->maxLength('username', 30)
			->maxLength('password', 64)
			->error();

		if (is_string($this->valid->valid())) {

			return $this->valid->valid();

		} else {
			$validacion = $this->validarUsuario($data);

			if (null !== $validacion) {
				$this->log->Looguer(
					'INFO',
					'Session',
					'Session iniciada',
					array('usuario' => $validacion[0]->nombre)
				);
				$this->session->set('user', $validacion);
				return true;
			} else {
				return false;
			}

		}

		
	}


	private function validarUsuario(array $data)
    {
        //$filter  = "/^[a-zA-Z0-9]*$/";
        
    	$pass = $this->cryp->encrypt($data['password']);



        $usuario = Usuarios::where('correo', "=", $data["username"])
        			->orWhere('nombre', "=", $data["username"])
            		->get();

        if(password_verify($pass, $usuario[0]->password)){
        	return $usuario;
        }else{
        	return null;
        }
    }
}
/*
$this->validar->params($data)
			->required('username', 'password')
			->minLength('username', 10)
			->minLength('password', 8)
			->maxLength('username', 30)
			->maxLength('password', 64)
			->error();

		if (is_string($this->validar->valid())) {
			$this->flash->error($this->validar->valid());
			return new RedirectResponse('/acceso/login');
		} else {
			$validacion = Usuarios::login($data);

			if (null !== $validacion) {
				$this->log->Looguer(
					'INFO',
					'Session',
					'Session iniciada',
					array('usuario' => $validacion[0]->nombre)
				);

				$this->session->set('user', $validacion);
				$this->flash->success('Session iniciada Bienvenido (a)');
				return new RedirectResponse('/admin/home');
			} else {
				$this->flash->error('Credenciales incorrectas');
				return new RedirectResponse('/acceso/login');
			}

		}
 */