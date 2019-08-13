<?php

namespace Core\session;

use \Core\session\SessionInterface;

/**
 * Funcion encargada de registrar los mensajes flash en la sesión
 */
class FlashMessage
{
    /**
     * [$session description]
     * @var [SessionInterface]
     */
    private $session;

    /**
     * [$key description]
     * Clave por defecto
     * @var string
     */
    private $key = "message_flash";

    /**
     * [$messagues description]
     * getMessagues()
     * @var [type]
     */
    private $messagues;

    private $template = [];
    /**
     * [__construct description]
     * @param SessionInterface $session [session]
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * [success description]
     * Funcion que define por defecto el mensaje success en caso de exito
     * @param  string $message [mensaje segun sea el caso]
     * @return [void]          [description]
     */
    public function success(string $message): void
    {
        $msg            = $this->session->get($this->key, []);
        $msg['success'] = '
            <div class="notification is-success" style="padding: 1em !important;">
              '.$message.'
            </div>
        ';
        $this->session->addFlash($this->key, $msg);
    }

    /**
     * [error description]
     * Funcion que define por defecto el mensaje de error
     * @param  string $message [description]
     * @return [void]          [description]
     */
    public function error(string $message): void
    {
        $msg          = $this->session->get($this->key, []);
        $msg['error'] = '
            <div class="notification is-danger" style="padding: 1em !important;">
              '.$message.'
            </div>
        ';
        $this->session->addFlash($this->key, $msg);
    }

    /**
     * [warning mensaje de advertencia]
     * @param  string $message [mensaje]
     * @return [void]
     */
    public function warning(string $message): void
    {
        $msg          = $this->session->get($this->key, []);
        $msg['warning'] = '
            <div class="notification is-warning" style="padding: 1em !important;">
              '.$message.'
            </div>
        ';
        $this->session->addFlash($this->key, $msg);
    }


    /**
     * [getMessague description]
     * Obtiene el mensaje que se establecio para cada caso de uso
     * @param  string $type [description]
     * @return [?string]       [description]
     */
    public function getMessague(string $type):  ? string
    {
        if (is_null($this->messagues)) {
            $this->messagues = $this->session->get($this->key, []);
            $this->session->delete($this->key);
        }
        if (array_key_exists($type, $this->messagues)) {
            return $this->messagues[$type];
        }
        return null;
    }
}
