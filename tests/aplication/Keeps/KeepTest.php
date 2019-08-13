<?php
namespace Tests\aplication\keeps;

use PHPUnit\Framework\TestCase;
use \Core\Keep\Keep;

class KeepTest extends TestCase
{

	

	public function testAddKeep()
	{
		$data = array(
			'nombre' => "pedro",
			'tel' => "1232445",
			'n3' => "tercer",
			'n4' => "cuarto",
			'n5' => "quinto",
			'n6' => "sexto"
		);

		$add = (new Keep()) 
			->addKeep($data)
			->getKeep('nombre', 'tel')
			->all();
			
		$this->assertEquals('pedro', (string)$add['nombre']);
		$this->assertEquals('1232445', (string)$add['tel']);
	}
}