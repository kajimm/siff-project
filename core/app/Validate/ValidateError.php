<?php
namespace Core\Validate;

/**
 * Calse que retorna el error segun la evaluación de cada campo
 * @author  freyder rey <freyder@siff.com.co>
 * @package siff-project
 */
class ValidateError
{
    /**
     * [$key campo que se esta evaluando]
     * @var [string]
     */
    private $key;

    /**
     * [$rule regla]
     * @var [string]
     */
    private $rule;

    /**
     * [$attribute attributos extra de cada funcion]
     * @example  minLength() pasa el numero que se establece para la descripción del error
     * @var [array]
     */
    private $attribute;

    /**
     * [$rules reglas]
     * @var [array]
     */
    private $rules = [
        'required' => "El campo %s es requerido",
        'index'    => "El valor de %s no es valido",
        'empty'    => "El Campo %s esta vacio",
        'min'      => "El valor %s supera el minimo permitido de %d",
        'max'      => "El valor %s supera el maximo permitido de %d",
        'number'   => "El valor %s debe contener solo numeros",
        'email'    => "El %s suministrado no es valido",
    ];

    /**
     * [__construct parametros que permiten la evaluacion y retorno del error correspondiente a la clave solicitada]
     * @param string $key       [campo que se esta evaluando]
     * @param string $rule      [regla que debe cumplir el campo evaluado]
     * @param array  $attribute [attributos extras]
     */
    public function __construct(string $key, string $rule, array $attribute = [])
    {
        $this->key       = $key;
        $this->rule      = $rule;
        $this->attribute = $attribute;
    }

    public function __toString()
    {

        $param = array_merge([$this->rules[$this->rule], $this->key], $this->attribute);
        return call_user_func_array('sprintf', $param);
    }
}
