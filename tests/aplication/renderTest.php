<?php

namespace Tests\aplication;

use PHPUnit\Framework\TestCase;
use \Core\Render\Renderer;
/**
 * 
 */
class renderTest extends TestCase
{
	
	public function setUp(): void{
		$this->renderer = new Renderer(__DIR__ . '/../../resources/views');
	}

	public function testTemplate(){

		$content = $this->renderer->render('home');

		$this->assertEquals('hola soy el home', $content);
	}
}