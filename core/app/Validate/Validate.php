<?php
namespace Core\Validate;

/**
 *Clase que permite la validación de los campos que se envian desde el cliente POST PUT DELETE
 * @author  freyder rey <freyder@siff.com.co>
 * @package siff project
 */
class Validate implements ValidateInterface
{
    /**
     * [$parametros parametros a validar]
     * @var [array ?? string]
     */
    private $parametros;

    /**
     * [$errors errores]
     * @var array
     */
    private $errors = [];

    public function params(array $parametros): self
    {
        $this->parametros = $parametros;
        return $this;
    }

    /**
     * [required establece que el campo en question bo puede estar vacio]
     * @param  [type] $campos [campos a evaluar]
     * @return [self]
     */
    public function required(string...$campos): self
    {
        foreach ($campos as $key) {
            $value = $this->getVal($key);

            if (is_null($value) || empty($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    /**
     * [index verifica que los indices y o parametros no contengan caracteres especiales]
     * @param  string $key [index o campo o valor a comprobar]
     * @return [self]
     */
    public function index(string $key): self
    {

        $indexKey = $this->getVal($key);

        $patron = "/^([a-z0-9]+-?)+$/";

        if (!is_null($indexKey) && !preg_match($patron, $indexKey)) {
            $this->addError($key, 'index');
        }
        return $this;
    }

    /**
     * [noEmpty valida que un campo no este vacio]
     * @param  string $key [campo a validar]
     * @return [self]
     */
    public function noEmpty(string...$keys): self
    {
        foreach ($keys as $key) {

            $value = $this->getVal($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    /**
     * [minLength establece el minimo de caracteres por un campo]
     * @param  string $key [campo a evaluar]
     * @param  int    $min [minimo permitido]
     * @return [self]
     */
    public function minLength(string $key, int $min): self
    {
        $value = $this->getVal($key);

        if (is_null($value) || strlen($value) < $min) {
            $this->addError($key, 'min', [$min]);
        }

        return $this;
    }

    /**
     * [maxLength establece el maximo permitido por campo]
     * @param  string $key [campo a evaluar]
     * @param  int    $max [maximo permitido]
     * @return [self]
     */
    public function maxLength(string $key, int $max): self
    {
        $value = $this->getVal($key);

        if (is_null($value) || strlen($value) > $max) {
            $this->addError($key, 'max', [$max]);
        }

        return $this;
    }

    /**
     * [number validar campos que solo contengan numeros]
     * @param  string $key [campo a evaluar]
     * @return [self]
     */
    public function number(string $key) : self
    {
        $value = $this->getVal($key);
        $patron = "/^([0-9]+?)+$/";

        if(!is_null($value) && !preg_match($patron, $value)){
            $this->addError($key, 'number');
        }
        return $this;
    }

    /**
     * [email valida que un correo sea valido]
     * @param  string $key [campo a evaluar]
     * @return [self]
     */
    public function email(string $key): self
    {
        $value = $this->getVal($key);
        $patron = '/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/';
        if(!is_null($value) && !preg_match($patron, $value)){
            $this->addError($key, 'email');
        }
       return $this;
    }

    /**
     * [error optiene los errores que se registren en la diferentes funciones]
     * @return [array]
     */
    public function error(): array
    {
        return $this->errors;
    }

    /**
     * [valid valida si hay errores retorna el mensaje del error de lo contrario retorna true]
     * @return [bool || string]
     */
    public function valid()
    {
        $response = '';
        if (empty($this->errors)) {
            $response = true;
        } else {
            foreach ($this->errors as $key => $v) {
                $response = (string) $this->errors[$key];
            }
        }
        return $response;
    }
    /**
     * [addError establece los errores]
     * @param string $key   [campo que no cumple]
     * @param string $regla [regla que no cumple]
     * @return  [ValidateError()]
     */
    private function addError(string $key, string $regla, array $attributes = [])
    {
        $this->errors[$key] = new ValidateError($key, $regla, $attributes);
    }

    /**
     * [getVal obtiene el valor de los parametros pasados a la funcion]
     * @param  string $key [description]
     * @return [type]      [description]
     */
    private function getVal(string $key)
    {
        if (array_key_exists($key, $this->parametros)) {
            return $this->parametros[$key];
        }
        return null;
    }
}
