<?php
namespace Tests\aplication\log;

use PHPUnit\Framework\TestCase;
use Core\Log\Log;

class LogTest extends TestCase
{

	private $log;

	public function setUp(): void
	{
		$this->log = new Log();	
	}

	public function testNewFile()
	{
		$file = $this->log->fileManager();
		$this->assertEquals($file, 'app/logs/log-2019-07-27.log');
	}
}