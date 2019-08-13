<?php

namespace Tests\aplication;

use \Core\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use App\modules\SiffModule;

/**
 *
 */
class appTest extends TestCase
{
    public function testRedirigir()
    {
        $app      = new App();
        $request  = new ServerRequest('GET', '/home/');
        $response = $app->run($request);
        $this->assertContains('/home', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testHome()
    {
        $app      = new App([
            SiffModule::class
        ]);
        $request  = new ServerRequest('GET', '/home');
        $response = $app->run($request);

        $this->assertStringContainsString('<h1>Hola mundo</h1>', (string) $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    

    
    public function testError()
    {
        $app      = new App();
        $request  = new ServerRequest('GET', '/xd');
        $response = $app->run($request);

        $this->assertStringContainsString('Not Found', (string) $response->getBody());
        $this->assertEquals(400, $response->getStatusCode());
    }
    
}
