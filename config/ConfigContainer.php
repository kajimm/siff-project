<?php
/**
 * Description
 * Definicion de uso para PHP-DI
 * En el caso de las interfaces facilitan la inyeccion de dependencias
 * @link http://php-di.org/
 * @author freyder rey <freyder@siff.com.co>
 * */
////////////////////////////////////////////////////////////////////////////
//        database        =>  Configuracion de conexion phinx             //
//        views_path      =>  ruta para carga de plantillas .twig         //
//        Twig_Extension  =>  Contiene las rutas de las extenciones       //
//                            que se creen o que twig traiga por defecto  //
//                            Ruta de extenciones core/app/Twig_Extension //
//                                                                        //
////////////////////////////////////////////////////////////////////////////

use function \DI\autowire;
use function \DI\create;
use function \DI\env;
use function \DI\factory;
use function \DI\get;
use \App\modules\Auth\authenticate\Access;
use \App\modules\Auth\authenticate\AccessInterface;
use \Core\Encryp\EncryptFactory;
use \Core\Encryp\EncryptInterface;
use \Core\Log\LogFactory;
use \Core\Log\LogInterface;
use \Core\Mail\MailerFactory;
use \Core\Mail\MailerInterface;
use \Core\Render\RendererInterface;
use \Core\Render\RenderFactory;
use \Core\Router\RouterFactory;
use \Core\session\Session;
use \Core\session\SessionInterface;
use \Core\Validate\Validate;
use \Core\Validate\ValidateInterface;
use \League\Route\RouteCollectionInterface;

return [
	'env' => env('ENV', 'dev'),
	'crypt' => [
		'secret_key' => 'af81a0fe398ab46fef0368feb147d95ffe238c56222ef4f14bcd9655e90cd880',
		'secret_iv' => 'c1738399df242358edaf0e0d5dabbfbbf999ca65cb4dd8b3a7fddd0cd2783693',
		'method' => 'AES-256-CBC',
	],
	'session_key' => '03c5f61211c9ba97eb614ccfb48b3d05d1e0d207611434d9e5d55688aedcb587',
	'MailerOptions' => [
		'host' => "host",
		'user' => "user",
		'pass' => "password",
		'port' => 587,
		'protocol' => 'tls',
	],
	'views_path' => dirname(__DIR__) . '/resources/views/',
	'Twig_Extension' => [
		get(\Core\Extension\TwigExtensionRouter::class),
		get(\Twig\Extension\DebugExtension::class),
		get(\Core\Extension\TwigExtensionAssets::class),
		get(\Core\Extension\TwigExtensionMessagueFlash::class),
		get(\Core\Extension\TwigExtensionSession::class),
		get(\Core\Extension\TwigExtensionCsrf::class)
	],
	RouteCollectionInterface::class => factory(RouterFactory::class),
	RendererInterface::class => factory(RenderFactory::class),
	MailerInterface::class => factory(MailerFactory::class),
	LogInterface::class => factory(LogFactory::class),
	SessionInterface::class => create(Session::class),
	EncryptInterface::class => factory(EncryptFactory::class),
	ValidateInterface::class => create(Validate::class),
	AccessInterface::class => create(Access::class)->constructor(get(SessionInterface::class), get(LogInterface::class), get(ValidateInterface::class), get(EncryptInterface::class)),
	//MIddlewares
	\Core\Middleware\pipe\SlashesMiddleware::class => autowire(),
	\Core\Middleware\pipe\RouterMiddleware::class => autowire(),
	\Core\Middleware\pipe\HttpMiddleware::class => autowire(),
	\Core\Middleware\pipe\CsrfMiddleware::class => autowire()->constructor(get(SessionInterface::class)),
	\Core\Middleware\pipe\VerifySessionMiddleware::class => autowire()->constructor(get(SessionInterface::class), get('session_key')),
	\Core\Middleware\pipe\InactivityMiddleware::class => autowire()->constructor(get(SessionInterface::class)),
];
