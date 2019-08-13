<?php

namespace Core;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Core\Middleware\Middleware;
use \DI\ContainerBuilder;

/**
 * Description
 * @method App      Clase encargada de inicializar las clases en el sistema
 * @author freyder rey <freyder@siff.com.co>
 * */
class App
{
    /**
     * [$modules description]
     * Modulos del sistema
     * @var array
     */
    private $modules = [];

    /**
     * Description
     * Recibe el la interface del objeto container PHP-DI
     * @var $container ContainerInterface
     */
    private $container;

    /**
     * [$config description]
     * Path definiciones del contenedor
     * @var [string]
     */
    private $config;

    /**
     * [$middleware description]
     * @var array MiddlewareInterface
     */
    private $middleware = [];

    /**
     * [$counter description]
     * Index middleware
     * @var integer
     */
    private $counter = 0;

    /**
     * [__construct description]
     * @param string $configContainer [Path definitions]
     */
    public function __construct(string $configContainer)
    {
        $this->config = $configContainer;
    }

    /**
     * Description
     * Agregar modulos al sistema
     * @param string
     * @return self
     */
    public function addModule(string $module): self
    {
        $this->modules[] = $module;

        return $this;
    }

    /**
     * [pipe description]
     * Registra los middlewares pasados en index-app.php
     * @param  string $method [description]
     * @return [self]         [description]
     */
    public function pipe(string $method): self
    {
        $this->middleware[] = $method;
        return $this;
    }

    /**
     * [process description]
     * Ejecuta los middlewares en el orden que se establezca
     * Se llama a la funcion getMiddleware la cual se encarga de retornar los middleware
     * @param  ServerRequestInterface  $request [description]
     * @param  RequestHandlerInterface $handler [description]
     * @return [ResponseInterface]              [description]
     */
    public function process(ServerRequestInterface $request): ResponseInterface
    {
        $getmiddleware = $this->getMiddleware();

        $midleware = new Middleware();

        foreach ($getmiddleware as $key) {
            $midleware->addMiddleware($key);
        }
        $response = $midleware->handle($request);
        return $response;
    }

    /**
     * [getMiddleware description]
     * Funcion que se encarga de recuperar los middleware usando como referencia
     * La funcion pipe() que recibe las clases como string
     * retorna array con los middlewares registrados en index-app.php
     * @return [array OR null] [array middleware]
     */
    public function getMiddleware(): array
    {
        if (array_key_exists($this->counter, $this->middleware)) {
            $midlle = [];
            foreach ($this->middleware as $key) {
                $midlle[] = $this->container->get($key);
            }
            return $midlle;
        }
        return null;
    }
    /**
     * Description
     * Manejador de las solicitudes http
     * Callback middleware
     * @param  ServerRequestInterface
     * @return [ResponseInterface]
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->process($request);
    }

    /**
     * Description
     * Obtiene y gestiona el contenedor de dependencias
     * PHP-DI
     * @return ContainerInterface
     */
    private function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $env     = getenv('ENV') ?? "production";

            //se debe modificar la condicional
            if ($env !== 'dev') {
                $builder->enableCompilation("app/cache/php-di");
            }
            $builder->useAutowiring(true);
            foreach ($this->modules as $module) {
                if ($module::DEFINITIONS) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }
            $builder->addDefinitions($this->config);
            $this->container = $builder->build();
        }
        return $this->container;
    }

    public function getModules()
    {
        return $this->modules;
    }
}
