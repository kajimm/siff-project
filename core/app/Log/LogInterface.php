<?php
namespace Core\Log;

/**
 * Core\audit\LogInterface;
 */
interface LogInterface
{

    public function newLog(string $type): void;
    /**
     * [manejador description]
     * crea el objeto de registro ruta y tipo de nivel a registrar
     * @return [void] [crear objetos enrutadores]
     */
    public function manejador(): void;

    /**
     * [getLevel description]
     * @param  string $type [nivel a recuperar]
     * @example
     *     DEBUG, ERROR, NOTICE etc
     * @return [INT]       [retorna INT identificador del nivel de log]
     */
    public function getLevel(string $type): int;
    /**
     * [registrar description]
     * Registra el evento en el log
     * @param  string $mensaje [descripcion del evento]
     * @param  array  $data    [datos auxiliares para identificar el evento]
     * @example inicio de session array('jhon' => 'doe')
     * @return [bool]          [true || false]
     */
    public function registrar(string $mensaje, array $data = []);

    /**
     * [rewriteChanel description]
     * Reescribe el canal de log
     * @example my_log.DEBUG => rewriteChanel('my_nuevo_log') => my_nuevo_log.DEBUG OR ERROR OR NOTICE
     * @param  string $newChanel [Nombre del nuevo canal]
     * @return [void]            [Resscribe nombre]
     */
    public function rewriteChanel(string $newChanel): void;
}
