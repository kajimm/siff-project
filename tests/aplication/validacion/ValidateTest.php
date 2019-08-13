<?php
namespace Tests\aplication\validacion;

use PHPUnit\Framework\TestCase;
use \Core\Validate\Validate;

class ValidateTest extends TestCase
{

    public function testError()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'pedro',
            ])
            ->required('name', 'apellido')
            ->error();

        $this->assertCount(1, $errors);
        $this->assertEquals('El campo apellido es requerido', (string) $errors['apellido']);
    }

    public function testSuccess()
    {
        $errors = (new Validate())
            ->params([
                'name'     => 'pedro',
                'apellido' => 'perez',

            ])
            ->required('name', 'apellido')
            ->error();

        $this->assertCount(0, $errors);
    }

    public function testIndexError()
    {
        $errors = (new Validate())
            ->params([
                'name'     => 'peAdro',
                'apellido' => 'perEe_',
                'numero'   => '3erA/',
            ])
            ->index('name')
            ->index('apellido')
            ->index('numero')
            ->index('pap') //valor null se omite su verificacion
            ->error();

        $this->assertCount(3, $errors);
        $this->assertEquals('El valor de name no es valido', (string) $errors['name']);
        $this->assertEquals('El valor de apellido no es valido', (string) $errors['apellido']);
        $this->assertEquals('El valor de numero no es valido', (string) $errors['numero']);
    }

    public function testIndexSuccess()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'pedro4567',
            ])
            ->index('name')
            ->error();

        $this->assertCount(0, $errors);
    }

    public function testnoEmptyError()
    {
        $errors = (new Validate())
            ->params([
                'name' => '',
            ])
            ->noEmpty('name')
            ->error();

        $this->assertCount(1, $errors);
        $this->assertEquals('El Campo name esta vacio', (string) $errors['name']);
    }

    public function testnoEmptySuccess()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'hola',
            ])
            ->noEmpty('name')
            ->error();
        $this->assertCount(0, $errors);
    }

    public function testMinlenghtError()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'ho',
            ])
            ->minLength('name', 8)
            ->error();
        $this->assertCount(1, $errors);
        $this->assertEquals('El valor name supera el minimo permitido de 8', (string) $errors['name']);
    }

    public function testMinlenghtSuccess()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'holaquert',
            ])
            ->index('name')
            ->minLength('name', 8)
            ->error();
        $this->assertCount(0, $errors);
    }

    public function testMaxlenghtError()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'hdjhdlkdlkjdlkjdlkjdlkjdlk',
            ])
            ->maxLength('name', 8)
            ->error();
        $this->assertCount(1, $errors);
        $this->assertEquals('El valor name supera el maximo permitido de 8', (string) $errors['name']);
    }

    public function testMaxlenghtSuccess()
    {
        $errors = (new Validate())
            ->params([
                'name' => 'holaquer',
            ])
            ->maxLength('name', 8)
            ->error();
        $this->assertCount(0, $errors);
    }


    public function testValidError()
    {
        $errors = new Validate();

            $errors->params([
                'name' => 'holaquer',
            ])
            ->required('name', 'apellido')
            ->error();

        $this->assertEquals('El campo apellido es requerido', $errors->valid());
    }

    public function testValidSuccess()
    {
        $errors = new Validate();

            $errors->params([
                'name' => 'holaquer',
                'apellido' => 'pepe'
            ])
            ->required('name', 'apellido')
            ->error();

        $this->assertTrue($errors->valid());
    }

    public function testNumberSuccess()
    {
        $errors = (new Validate())
            ->params([
                'name' => '3138979275'
            ])
            ->required('name')
            ->number('name')
            ->error();

        $this->assertCount(0, $errors);
    }

    public function testNumberError()
    {
        $errors = (new Validate())
            ->params([
                'name' => '3138979gsh'
            ])
            ->required('name')
            ->number('name')
            ->error();

        $this->assertCount(1, $errors);
        $this->assertEquals('El valor name debe contener solo numeros', (string)$errors['name']);
    }

    public function testEmailError()
    {
        $errors = (new Validate())
            ->params([
                'email' => 'jhon@doe'
            ])
            ->email('email')
            ->error();

        $this->assertCount(1, $errors);
        $this->assertEquals('El email suministrado no es valido', (string)$errors['email']);
    }

    public function testEmailSuccess()
    {
        $errors = (new Validate())
            ->params([
                'email' => 'jhon@doe.com'
            ])
            ->email('email')
            ->error();

        $this->assertCount(0, $errors);
    }
}
