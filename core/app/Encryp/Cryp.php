<?php
namespace Core\Encryp;

use Core\Encryp\EncryptInterface;

class Cryp implements EncryptInterface
{
    private $secret_key;
    private $secret_iv;
    private $method;
    private $tokens = [];

    public function __construct(string $secret_key, string $secret_iv, string $method)
    {

        $this->secret_key = $secret_key;
        $this->secret_iv  = $secret_iv;
        $this->method     = $method;

    }

    public function encrypt($password):  ? string
    {
        $output = false;
        $key    = hash('sha256', $this->secret_key);
        $iv     = substr(hash('sha256', $this->secret_iv), 0, 16);
        $output = openssl_encrypt($password, $this->method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public function decrypt(string $password) :  ? string
    {
        $key    = hash('sha256', $this->secret_key);
        $iv     = substr(hash('sha256', $this->secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($password), $this->method, $key, 0, $iv);
        return $output;
    }

    public function validator(string $token) : bool
    {
    	$confirmar = false;
    	$t = $this->useToken();
    	if(in_array($token, $t)){
            $confirmar = true;
        }
    	return $confirmar;
    }

    public function csrfToken(): void
    {
        $this->tokens[] = bin2hex(random_bytes(32));
    }

    public function useToken(): ?array
    {
    	return $this->tokens;
    }

}
