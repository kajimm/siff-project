<?php
namespace App\modules\Auth\authenticate;

use \App\models\Usuarios;

/**
 * 
 */
class Access implements AccessInterface
{
	/**
	 * [$session]
	 * @var [SessionInterface]
	 */
	private $session;

	/**
	 * [$log]
	 * @var [LogInterface]
	 */
	private $log;

	/**
	 * [$valid]
	 * @var [ValidateInterface]
	 */
	private $valid;

	/**
	 * [$cryp]
	 * @var [EncryptInterface]
	 */
	private $cryp;

	/**
	 * [__construct description]
	 * @param [type] $session [SessionInterface]
	 * @param [type] $log     [LogInterface]
	 * @param [type] $valid   [ValidateInterface]
	 * @param [type] $cryp    [EncryptInterface]
	 */
	public function __construct($session, $log, $valid, $cryp)
	{
		$this->session = $session;
		$this->log = $log;
		$this->valid = $valid;
		$this->cryp = $cryp;	
	}

	/**
	 * [login]
	 * Establece la session si es usuario que intenta ingresar existe en la base de datos
	 * @param  array  $data [Parametros de identificacion del usuario]
	 * @return [string || bool]
	 */
	public function login(array $data)
	{
		$this->valid->params($data)
			->required('nombre', 'password')
			->minLength('nombre', 4)
			->minLength('password', 8)
			->maxLength('nombre', 30)
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


	public function logout()
	{
		if ($this->session->get('user')) {
			$this->log->Looguer(
				'NOTICE',
				'sessiones',
				'Session finalizada',
				array('Usuario' => $this->session->get('user')['nombre'])
			);
			$this->session->delCookie();
        	$this->session->destroy();
        	return true;
        }else{
        	return false;
        }
	}

	/**
	 * [validarUsuario]
	 * Funcion que permite validar el usuario en la base de datos y lo retorna en caso de que exista
	 * @param  array  $data [Parametros de busqueda]
	 * @return [array | null]
	 */
	private function validarUsuario(array $data)
    {
    	$pass = $this->cryp->encrypt($data['password']);
        $usuario = Usuarios::where('correo', "=", $data["nombre"])
        			->orWhere('nombre', "=", $data["nombre"])
            		->get();
        if(password_verify($pass, $usuario[0]->password)){
        	return $usuario;
        }else{
        	return null;
        }
    }
}
