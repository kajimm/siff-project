<?php
namespace Core\Strategies;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 *Description
 * Clase que perzonaliza los errores http
 */
class Error implements MiddlewareInterface
{
    private $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    private function template($error, $description): string
    {
        return '<!doctype html>
                <html lang="es">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>ERROR::404</title>
                    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
                    <style>
                        html,
                        body {
                            background-color: #fff;
                            color: #636b6f;
                            font-family: "Nunito", sans-serif;
                            font-weight: 200;
                            height: 100vh;
                            margin: 0;
                        }
                        .full-height {
                            height: 100vh;
                        }
                        .flex-center {
                            align-items: center;
                            display: flex;
                            justify-content: center;
                        }
                        .position-ref {
                            position: relative;
                        }
                        .content {
                            text-align: center;
                        }
                        .title {
                            font-size: 84px;
                            text-transform: uppercase;
                        }
                        .links>a {
                            color: #636b6f;
                            padding: 0 25px;
                            font-size: 13px;
                            font-weight: 600;
                            letter-spacing: .1rem;
                            text-decoration: none;
                            text-transform: uppercase;
                        }
                        .m-b-md {
                            margin-bottom: 30px;
                        }
                    </style>
                </head>
                <body>
                    <div class="flex-center position-ref full-height">
                        <div class="content">
                            <div class="title m-b-md"> ' . $error . ' </div>
                            <p> Error:' . $description . ' </p>
                            <div class="links"> <a href="javascript:history.back()">Regresar</a> </div>
                        </div>
                    </div>
                </body>
                </html>';
    }

    /**
     * Description
     * Valida que el error sea una instancia de NotFoundException y retorna un template perzonalizado
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->exception instanceof \League\Route\Http\Exception\NotFoundException) {
            $response = new Response();
            $response->getBody()->write($this->template(404, '404 Pagina no encontrada'));
            return $response->withAddedHeader('content-type', 'text/html')->withStatus(404);
        }
    }
}
