<?php

namespace Core\Log;

use Core\Log\LogInterface;

/**
 *
 */
class Log implements LogInterface
{
    /**
     * [$logger description]
     * @var [Object]
     */
    private $logger;

    /**
     * [$firePhp description]
     * Variable que permite el cambio de canal al registro de log
     * @var [Object]
     */
    private $firePhp;

    /**
     * [$level description]
     * recibe object \Monolog\Logger::LEVEL;
     * @var [INT]
     */
    private $level;

    /**
     * [$type description]
     * Funciona como identificador del tipo de nivel de registro que se desea hacer
     * @var [string]
     */
    private $type;

    /**
     * [$path]
     * Ruta archivo .log
     * @var [string]
     */
    private $path = "app/logs/";

    /**
     * [$nameDefault indicador de cada archivo de log-2019-07-01.log]
     * @var string
     */
    private $nameDefault = 'log-';

    /**
     * [$file guarda el archivo que se genero para no estar generandolo cada vez que se use]
     * @var [string]
     */
    private $file;

    public function __construct()
    {
        $this->logger = new \Monolog\Logger('Blaze_log');
        $this->file   = $this->fileManager();
    }

    /**
     * [fileManager funcion que genera archivos log dinamicamente segun la fecha]
     * Permite controlar las acciones del sistema dividiendolos por dias
     * @return [string] [nuevo archivo o verifica si ya esta creado y lo retorna]
     */
    public function fileManager()
    {
        $archivo = $this->path . $this->nameDefault . date("Y-m-d") . ".log";
        $default = null;
        if (file_exists($archivo)) {
            return $archivo;
        } else {
            $file = fopen($archivo, "w+b");
            if (file_exists($archivo)) {
                $default = $archivo;
            } else {
                $default = null;
            }
            fclose($file);
        }
        return $default;
    }

    /**
     * [newLog description]
     * Se encarga de registrar un nuevo log del sistema
     * @param  string $type [Identificador de nivel de registro]
     * @return [void]       [Ejecuta el registro]
     */
    public function newLog(string $type): void
    {
        $this->type  = $type;
        $this->level = $this->getLevel($this->type);
        $this->manejador();
    }

    public function manejador(): void
    {
        $this->logger->pushHandler(new \Monolog\Handler\StreamHandler($this->file, $this->level));
        $this->firePhp = new \Monolog\Handler\FirePHPHandler();
    }

    /**
     * [getLevel description]
     * @param  string $type [nivel a recuperar]
     * @example
     *     DEBUG, ERROR, NOTICE etc
     * @return [INT]       [retorna INT identificador del nivel de log]
     */
    public function getLevel(string $type): int
    {
        switch ($type) {
            case 'DEBUG':
                return \Monolog\Logger::DEBUG;
                break;
            case 'INFO':
                return \Monolog\Logger::INFO;
                break;
            case 'NOTICE':
                return \Monolog\Logger::NOTICE;
                break;
            case 'WARNING':
                return \Monolog\Logger::WARNING;
                break;
            case 'ERROR':
                return \Monolog\Logger::ERROR;
                break;
            case 'CRITICAL':
                return \Monolog\Logger::CRITICAL;
                break;
            case 'ALERT':
                return \Monolog\Logger::ALERT;
                break;
            case 'EMERGENCY':
                return \Monolog\Logger::EMERGENCY;
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * [registrar description]
     * Registra el evento en el log
     * @param  string $mensaje [descripcion del evento]
     * @param  array  $data    [datos auxiliares para identificar el evento]
     * @example inicio de session array('jhon' => 'doe')
     * @return [bool]          [true || false]
     */
    public function registrar(string $mensaje, array $data = [])
    {
        switch ($this->type) {
            case 'DEBUG':
                $this->logger->debug($mensaje, $data);
                break;
            case 'INFO':
                $this->logger->info($mensaje, $data);
                break;
            case 'NOTICE':
                $this->logger->notice($mensaje, $data);
                break;
            case 'WARNING':
                $this->logger->warning($mensaje, $data);
                break;
            case 'ERROR':
                $this->logger->error($mensaje, $data);
                break;
            case 'CRITICAL':
                $this->logger->crit($mensaje, $data);
                break;
            case 'ALERT':
                $this->logger->alert($mensaje, $data);
                break;
            case 'EMERGENCY':
                $this->logger->emerg($mensaje, $data);
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * [rewriteChanel description]
     * Reescribe el canal de log
     * @example my_log.DEBUG => rewriteChanel('my_nuevo_log') => my_nuevo_log.DEBUG OR ERROR OR NOTICE
     * @param  string $newChanel [Nombre del nuevo canal]
     * @return [void]            [Resscribe nombre]
     */
    public function rewriteChanel(string $newChanel): void
    {
        $this->logger = new \Monolog\Logger($newChanel);
        $this->level  = $this->getLevel($this->type);
        $this->logger->pushHandler(
            new \Monolog\Handler\StreamHandler($this->file, $this->level)
        );
        $this->logger->pushHandler($this->firePhp);
    }

    /**
     * [Looguer funcion por default para generar registros de log del sistema para evitar llamar a todas la funciones]
     * @param string $level   [Nivel de log INFO ERROR NOTICE]
     * @param string $canal   [tipo de mensaje o accion que se esta generando sessiones, ediciones, eliminacion]
     * @param string $mensaje [descripción de la accion que se ejecuto]
     * @param array  $params  [array para agregar informacion extra al log]
     */
    public function Looguer(string $level, string $canal = 'Blaze_log', string $mensaje, array $params = [])
    {
        $this->newLog($level);
        $this->rewriteChanel($canal);
        $this->registrar($mensaje, $params);
    }
}
