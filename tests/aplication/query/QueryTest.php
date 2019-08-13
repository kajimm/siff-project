<?php
namespace Tests\aplication\query;

use Core\Query\QueryBuilder;
use Core\Query\Select;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{

    private $query;
    

    public function setUp(): void
    {
        $this->query = new QueryBuilder();

    }

    
    public function testClaseSelect(){

    	$sql = (new Select('users', 'nombre, apellido'))->get();

    	$this->assertEquals("SELECT nombre, apellido FROM users", (string)$sql);

    }

    public function testSqlSimple()
    {
        $sql = $this->query->from('users')
        					->select('nombre', 'edad');
        $this->assertEquals('SELECT nombre, edad FROM users', (string)$sql);
        
    }

    public function testSqlWhere()
    {
        $sql = $this->query->from('users', 'p')
        					->where('id = :id OR nombre = :nombre', 'q = i');

        $this->assertEquals('SELECT * FROM users AS p WHERE (id = :id OR nombre = :nombre) AND (q = i)', (string)$sql);
    }

    public function testSqlWhereNotAnd()
    {
        $sql = $this->query->from('users')
                            ->where('id = :id OR nombre = :nombre');

        $this->assertEquals('SELECT * FROM users WHERE (id = :id OR nombre = :nombre)', (string)$sql);
    }

    /**
     * [testExecute retorna la query completa para ejecutar]
     * @return [type] [description]
     */
     public function testExecute()
    {
        $sql = $this->query->from('users', 'p')
        					->select('nombre')
        					->where('id = :id OR nombre = :nombre', 'q = i')
        					->execute();
        $this->assertEquals('SELECT nombre FROM users AS p WHERE (id = :id OR nombre = :nombre) AND (q = i)', (string)$sql);
    }

    public function testDelete(){
    	$sql = $this->query->delete('users')
    					    ->where('id = :id OR nombre = :nombre', 'q = i');

    	$this->assertEquals('DELETE FROM users WHERE (id = :id OR nombre = :nombre) AND (q = i)', (string)$sql);
    }

    public function testInsert(){
    	$sql = $this->query->insert('users')
    					   ->into('nombre, edad')
    					   ->values(':nombre, :edad');
    	$this->assertEquals('INSERT INTO users (nombre, edad) VALUES (:nombre, :edad)', (string)$sql);
    }

    public function testUpdate(){
    	$sql = $this->query->update('users')
    				->set('nombre = :nombre, edad = :edad')
    				->where('id = :id');
    	$this->assertEquals('UPDATE users SET nombre = :nombre, edad = :edad WHERE (id = :id)', (string)$sql);
    }


    public function testLimit()
    {
        $sql = $this->query->from('users', 'user')
                            ->where('id = :id', 'name = :name')
                            ->limit(1)
                            ->execute();
        $this->assertEquals('SELECT * FROM users AS user WHERE (id = :id) AND (name = :name) LIMIT (1)', (string)$sql);
    }
    

    public function testOrderByASC()
    {
        $sql = $this->query->from('users')
                            ->select('nombre', 'edad')
                            ->orderBy('clientes', 'frecuencia', 'ASC')
                            ->execute();
        $this->assertEquals('SELECT nombre, edad FROM users ORDER BY clientes, frecuencia ASC', (string)$sql);
    }

    
    public function testOrderByDESC()
    {
        $sql = $this->query->from('users')
                            ->select('nombre', 'edad')
                            ->orderBy('id', 'DESC')
                            ->execute();
        $this->assertEquals('SELECT nombre, edad FROM users ORDER BY id DESC', (string)$sql);
    }
   

}
