<?php declare (strict_types = 1);
namespace Core\Strategies;

use Throwable;
use \Core\Strategies\Error;
use \League\Route\ContainerAwareInterface;
use \League\Route\ContainerAwareTrait;
use \League\Route\Http\Exception as HttpException;
use \League\Route\Http\Exception\MethodNotAllowedException;
use \League\Route\Http\Exception\NotFoundException;
use \League\Route\Route;
use \League\Route\Strategy\AbstractStrategy;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

/**
 * Description
 * Estrategia perzonalizada que se usa en las rutas es una capa de perzonalizacion para maneho de errores
 * e inyeccion de dependencias
 * @author League\Router
 * */
class StrategySiff extends AbstractStrategy implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var \Psr\Http\Message\ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * Construct.
     *
     * @param \Psr\Http\Message\ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;

        $this->addDefaultResponseHeader('content-type', 'text/html');
    }

    /**
     * {@inheritdoc}
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): Response
    {
        $controller = $route->getCallable($this->getContainer());

        // probar errores var_dump($this->getContainer()->get(\Core\Render\RenderInterface::class));

        $response = $controller($request, $route->getVars());

        if ($this->isJsonEncodable($response)) {
            $body     = json_encode($response);
            $response = $this->responseFactory->createResponse(200);
            $response->getBody()->write($body);
        }

        $response = $this->applyDefaultResponseHeaders($response);

        return $response;
    }

    /**
     * Check if the response can be converted to JSON
     *
     * Arrays can always be converted, objects can be converted if they're not a response already
     *
     * @param mixed $response
     *
     * @return bool
     */
    protected function isJsonEncodable($response): bool
    {
        if ($response instanceof Response) {
            return false;
        }

        return (is_array($response) || is_object($response));
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return new Error($exception);
        //return $this->buildJsonResponseMiddleware($exception);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return $this->buildJsonResponseMiddleware($exception);
    }

    /**
     * Return a middleware the creates a JSON response from an HTTP exception
     *
     * @param \League\Route\Http\Exception $exception
     *
     * @return \Psr\Http\Server\MiddlewareInterface
     */
    protected function buildJsonResponseMiddleware(HttpException $exception): MiddlewareInterface
    {

        return new class($this->responseFactory->createResponse(), $exception) implements MiddlewareInterface
        {
            protected $response;
            protected $exception;

            public function __construct(Response $response, HttpException $exception)
            {
                $this->response  = $response;
                $this->exception = $exception;
            }

            public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): Response
            {
                $this->exception->buildJsonResponse($this->response);
            }
        };
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionHandler(): MiddlewareInterface
    {
        return $this->getThrowableHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function getThrowableHandler(): MiddlewareInterface
    {
        return new class($this->responseFactory->createResponse()) implements MiddlewareInterface
        {
            protected $response;

            public function __construct(Response $response)
            {
                $this->response = $response;
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $requestHandler
            ): Response {
                try {
                    return $requestHandler->handle($request);
                } catch (Throwable $exception) {
                    $response = $this->response;

                    if ($exception instanceof HttpException) {
                        
                        return $exception->buildJsonResponse($response);
                    }
                    
                    $response->getBody()->write($exception->getMessage());

                    $response = $response->withAddedHeader('content-type', 'text/html');
                    return $response->withStatus(500, strtok($exception->getMessage(), "\n"));
                }
            }
        };
    }
}
