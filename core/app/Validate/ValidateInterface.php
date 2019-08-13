<?php
namespace Core\Validate;

interface ValidateInterface
{
    public function params(array $parametros);

    /**
     * [required establece que el campo en question bo puede estar vacio]
     * @param  [type] $campos [campos a evaluar]
     * @return [self]
     */
    public function required(string...$campos);

    /**
     * [index verifica que los indices y o parametros no contengan caracteres especiales]
     * @param  string $key [index o campo o valor a comprobar]
     * @return [self]
     */
    public function index(string $key);

    /**
     * [noEmpty valida que un campo no este vacio]
     * @param  string $key [campo a validar]
     * @return [self]
     */
    public function noEmpty(string...$keys);

    /**
     * [minLength establece el minimo de caracteres por un campo]
     * @param  string $key [campo a evaluar]
     * @param  int    $min [minimo permitido]
     * @return [self]
     */
    public function minLength(string $key, int $min);

    /**
     * [maxLength establece el maximo permitido por campo]
     * @param  string $key [campo a evaluar]
     * @param  int    $max [maximo permitido]
     * @return [self]
     */
    public function maxLength(string $key, int $max);
}
