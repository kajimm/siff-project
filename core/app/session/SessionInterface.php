<?php

namespace Core\session;

/**
 * Interface para manejo de sessiones  mesanjes flash y tokens csrf
 * @author  freyder rey <freyder@siff.com.co>
 * @pakage blaze framework
 */
interface SessionInterface
{
	/**
	 * [start iniciaer session]
	 * @return [void]
	 */
	public function start(): void;

	/**
	 * [set establece una session]
	 * @param string $key   [identificador]
	 * @param $value [valor del identificador]
	 * @return  [void]
	 */
    public function set(string $key, $value): void;

    /**
     * [get obtener la session por identificador]
     * @param  string $key     [identificador de la session]
     * @param  [?null] $default [null]
     * @return [?array]          [array session ?? null ?? []]
     */
    public function get(string $key, $default = null);

    /**
     * [delete elimina usa session por su identificador]
     * @param  string $key [identificador de session]
     * @return [void]
     */
    public function delete(string $key): void;
}
