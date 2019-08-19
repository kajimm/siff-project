<?php
namespace Core\Capcha;

/**
 * 
 */
class Capcha
{
	/**
	 * [$clave]
	 * clave secreta
	 * @var [string]
	 */
	private $clave;

	public function __construct($secret_key)
	{
		$this->clave = $secret_key;
	}

	/**
	 * [check]
	 * Valida el token generado por el capcha en la url de goggle
	 * @param  [string] $code [Token generado por el cliente]
	 * @return [bool]
	 */
	public function check(string $code)
	{
		$url = "https://www.google.com/recaptcha/api/siteverify?secret={$this->clave}&response={$code}";
		
		if(function_exists('curl_version')){
			$init = curl_init($url);
			curl_setopt($init, CURLOPT_HEADER, false);
			curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($init, CURLOPT_TIMEOUT, 1);
			curl_setopt($init, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($init);
		}else{
			$response = file_get_contents($url);
		}

		$return  = json_decode($response);
		return $return->success;
	}
}