<?php
namespace Core\session;

class SessionFile implements \SessionHandlerInterface
{
    private $location;
    private $prefix;

    /**
     * [__construct inicializa las variables de ruta de almacenamiento y nombre del archivo]
     * @param string $prefijo [nombre inicial de la session archivo]
     */
    public function __construct($prefijo = '')
    {
        $this->location = './core/app/session/storage';
        $this->prefix   = $prefijo;
    }

    public function close()
    {
        return true;
    }

    /**
     * [destroy Elimina la session]
     * @param  [type] $sessionID [session_id()]
     * @return [bool]            [true ? false]
     */
    public function destroy($sessionID)
    {
        $file = $this->location . DIRECTORY_SEPARATOR . $this->prefix . $sessionID;
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }
    /**
     * Clean sessions
     * @param  string  $maxlifetime the session maxlifetime
     * @return bool                 was gc executed OK
     */
    public function gc($maxlifetime)
    {
        foreach (glob($this->location) as $name) {
            $file = $this->location . DIRECTORY_SEPARATOR . $name;
            if ((!$this->prefix || strpos($name, $this->prefix) === 0) &&
                is_file($file) &&
                filemtime($file) + $maxlifetime < time()
            ) {
                @unlink($file);
            }
        }
        return true;
    }
    /**
     * Open a session
     * @param  string $path path
     * @param  string $name session name
     * @return bool         was open OK
     */
    public function open($path, $name)
    {
        if (!is_dir($this->location) && !mkdir($this->location, 0755, true)) {
            throw new \Exception('Could not open session storage dir');
        }
        return true;
    }
    /**
     * Read a session
     * @param  string $sessionID the session ID
     * @return string            the session data
     */
    public function read($sessionID)
    {
        return (string) @file_get_contents($this->location . DIRECTORY_SEPARATOR . $this->prefix . $sessionID);
    }
    /**
     * Write session data
     * @param  string $sessionID   the session ID
     * @param  string $sessionData the sessino data
     * @return bool                was the write successful
     */
    public function write($sessionID, $sessionData)
    {
        return @file_put_contents(
            $this->location . DIRECTORY_SEPARATOR . $this->prefix . $sessionID,
            $sessionData
        ) !== false;
    }
}
