<?php
namespace Core\session;

use Core\session\SessionFile;
use Core\session\SessionInterface;
use GuzzleHttp\Psr7\Response;

/**
 * Clase de inicio de session, implementa mensajes flash
 * @author  freyder rey <freyder@siff.com.co>
 * @link www.github.com/kajim
 */
class Session implements SessionInterface
{
    /**
     * [$nameSession description]
     * identificado de la session
     * @var [string]
     */
    private $nameSession;
    /**
     * [$result description]
     * @var [ResponseInterface => answer()]
     */
    private $result;

    /**
     * [$cookie $_COOKIE init]
     * @var [$_COOKIE]
     */
    private $cookie;

    private $count = 1;

    /**
     * [start inicia session]
     * @return [void]
     */
    public function start(): void
    {

        if (session_status() === PHP_SESSION_NONE) {
            $set = new SessionFile('__blaze_session');
            $this->init();
            session_set_save_handler($set, true);
            session_start();
        }
    }

    /**
     * [init]
     * Manejo de cookies
     * @return [void]
     */
    public function init(): void
    {
        ini_set('session.use_cookies', true);
        ini_set("session.entropy_file", "/dev/urandom");
        ini_set("session.entropy_length", "32");
        ini_set('session.session.hash_bits_per_character', 6);
        ini_set('session.use_only_cookies', true);
        ini_set('session.use_trans_sid', false);
        ini_set('session.session.use_strict_mode', true);
        if (!(int) ini_get('session.gc_probability') || !(int) ini_get('session.gc_divisor')) {
            ini_set('session.gc_probability', '1');
            ini_set('session.gc_divisor', '100');
        }
        $this->cookie = array(
            'name'     => '__blaze',
            'path'     => '/',
            'lifetime' => 0,
            'domain'   => 'localhost',
            'httponly' => true);
        ini_set('session.name', $this->cookie['name']);
        foreach ($this->cookie as $setting => $value) {
            ini_set('session.cookie_' . $setting, $value);
        }
    }

    /**
     * [set]
     * Establece una nueva session
     * @param string $name   [nombre o identificador dela session]
     * @param [?array] $values [valores que almacenara la session]
     * @return [void]
     */
    public function set(string $name, $values): void
    {
        $this->regenerate();
        $this->start();
        $validacion = preg_match('/[a-zA-Z]+/', $name);
        if ($validacion === 1) {
            $this->nameSession = $name;
            $user              = array(
                'nombre' => $values[0]->nombre,
                'id'     => hash('sha256', $_SERVER['HTTP_USER_AGENT']),
                'time'   => ($_SERVER['REQUEST_TIME'] + 3600),
            );
            $_SESSION[$this->nameSession] = $user;
        } else {
            throw new \Exception("Error identificador no valido {" . $name . "}", 1);
        }
    }

    /**
     * [get]
     * obtiene la session del usuario
     * @param  string $key     [identificado de la session]
     * @param  [type] $default [null || valor que se le asigne]
     * @return [array]          [array || null]
     */
    public function get(string $key, $default = null)
    {
        $this->start();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * [addFlash description]
     * registra los mensaje flash
     * $key => $msg array asociativo ver $_SESSION
     * @param string $key [identificado de la sesion o nombre]
     * @param [?string] $msg [mensaje flash]
     */
    public function addFlash(string $key, $msg) : void
    {
        $this->start();
        $_SESSION[$key] = $msg;
    }

    /**
     * [addToken Registra los tokens en la session]
     * @param string $key [identificador se session]
     * @param [type] $val [valor del identificador]
     * @return [void]
     */
    public function addToken(string $key, $val): void
    {
        $_SESSION[$key] = $val;
    }

    /**
     * [intentos intentos de session]
     * @return [type] [description]
     */
    public function attemps(string $key, $value): void{
        $this->start();
        if(null === $this->get('intentos')){
            $_SESSION[$key] = $value;
        }else{
            $_SESSION[$key] = $_SESSION[$key] + $value;
        }
    }


    /**
     * [delete description]
     * Elimina una parte de la session actual $_SESSION[$key]
     * Parte del array asociativo
     * @param  string $key [identificado de la session]
     * @return [void]
     */
    public function delete(string $key): void
    {
        $this->regenerate();
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
    /**
     * [destroy Elimina la sesion actual]
     * @return [void]
     */
    public function destroy(): void
    {
        $this->regenerate();
        session_unset();
        session_destroy();
        $file = null;
        foreach (glob("core/app/session/storage/__blaze_session*") as $key) {
            if (false === filesize($key)) {
               @unlink($key);
            }
            
        }
    }

    /**
     * [delCookie elimina la cookie de session]
     * @return [void]
     */
    public function delCookie(): void
    {
        setcookie('__blaze', "", time() - 3600);
    }

    /**
     * [regenerate regenera el id de la session]
     * @return [void]
     */
    private function regenerate(): void
    {
        $this->start();
        session_id();
        session_regenerate_id(true);
        session_id();
        foreach (glob("core/app/session/storage/__blaze_session*") as $key) {
            if (false === filesize($key)) {
                @unlink($key);
            }
        }
    }

    /**
     * [salt ]
     * @return [type] [description]
     */
    private function salt(): string
    {
        $key  = "03c5f61211c9ba97eb614ccfb48b3d05d1e0d207611434d9e5d55688aedcb587";
        $hash = password_hash($_SERVER['HTTP_USER_AGENT'] . $key, PASSWORD_DEFAULT);
        return $hash;
    }
}
