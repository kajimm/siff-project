<?php
namespace App\modules\Auth\authenticate;

use \App\models\Usuarios;

/**
 *Clase encargada de manejar las solicitudes de
 * Login, Registro, Recuperar contraseña
 * La injeccion de clases se hace directamente en ConfigContainer revisar
 * @author  freyder rey <freyder@siff.com.co>
 * @package  siff-project
 * @link  https://github.com/kajimm/siff-project
 */
class Access implements AccessInterface {
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
	public function __construct($session, $log, $valid, $cryp) {
		$this->session = $session;
		$this->log = $log;
		$this->valid = $valid;
		$this->cryp = $cryp;
	}

	/**
	 * [register]
	 * Funcion que registrar nuevos usuarios en la base de datos del sistema
	 * @param  array  $datos [Parametros de registro]
	 * @return [?bool]
	 */
	public function register(array $datos): ?bool {

		$this->valid->params($datos)
			->required('nombre', 'apellido', 'correo', 'telefono', 'password', 'tyc')
			->minLength('nombre', 5)
			->minLength('apellido', 5)
			->minLength('telefono', 8)
			->minLength('password', 8)
			->maxLength('nombre', 50)
			->maxLength('apellido', 50)
			->maxLength('telefono', 11)
			->noEmpty('nombre', 'apellido', 'correo', 'telefono', 'password')
			->email('correo')
			->number('telefono')
			->error();

		if(is_string($this->valid->valid())){
			return $this->valid->valid();
		}else{

			$crear= $this->createUser($datos);

			if($crear){
				$this->log->Looguer(
					'NOTICE',
					'Registro',
					'Usuario dado de alta',
					 array('Usuario' => $datos['nombre'])
				);
				//aqui va queue 
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * [login]
	 * Establece la session si es usuario que intenta ingresar existe en la base de datos
	 * @param  array  $data [Parametros de identificacion del usuario]
	 * @return [string || ?bool]
	 */
	public function login(array $data): ?bool {
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
			$validacion = $this->validarLogin($data);

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

	/**
	 * [logout]
	 * Funcion que finaliza la session de usuarios
	 * @return [void]
	 */
	public function logout(): bool  {
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
		} else {
			return false;
		}
	}

	/**
	 * [validarUsuario]
	 * Funcion que permite validar el usuario en la base de datos para efectos de inicio de session
	 * @param  array  $data [Parametros de busqueda]
	 * @return [array | null]
	 */
	private function validarLogin(array $data) {
		$pass = $this->cryp->encrypt($data['password']);
		$usuario = Usuarios::where('correo', "=", $data["nombre"])
			->orWhere('nombre', "=", $data["nombre"])
			->get();
		if (password_verify($pass, $usuario[0]->password)) {
			return $usuario;
		} else {
			return null;
		}
	}


	/**
	 * [existsUser]
	 * Funcion que permite validar la existencia de un usuario previamente registrado 
	 * Evita la duplicidad de informacion precisa
	 * @example correo
	 * @param  string $correo [Parametro a validar]
	 * @return [bool] 
	 */
	private function existsUser(string $correo): bool
	{
		$mail = Usuarios::where("correo", "=", $correo)->get();
		if(sizeof($mail) > 0){
			return false;
		}
		return true;
	}



	/**
	 * [createUser]
	 * Agregar un nuevo usuario a la base de datos
	 * @param  array  $datos [Parametros de usuario]
	 * @return [bool]
	 */
	private function createUser(array $datos): bool
	{
		$filter = [];
		$pass1 = $this->cryp->encrypt($datos['password']);
		$pass2 = password_hash($pass1, PASSWORD_DEFAULT);
		$datos['password'] = $pass2;

		foreach ($datos as $k => $v) {
			$filter[$k] = $v;
		}

		$validar = $this->existsUser($datos['correo']);
		if($validar){
			Usuarios::create($datos);
			return true;
		}else{
			return false;
		}
	}
}
