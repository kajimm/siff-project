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
use \Core\Capcha\Capcha;
use Symfony\Component\Yaml\Yaml;

$key = Yaml::parseFile('keys_siff_project.yaml');

return [
	'env' => env('ENV', 'dev'),
	'crypt' => [
		'secret_key' => $key['cryp']['key'],
		'secret_iv'  => $key['cryp']['iv'],
		'method' 	 => $key['cryp']['method'],
	],
	'session_key' 	 => $key['session']['key'],
	'secret_capcha'  => $key['capcha']['secret_key'],
	'MailerOptions'  => [
		'host' 		 => $key['mail']['host'],
		'user'		 => $key['mail']['user'],
		'pass' 		 => $key['mail']['pass'],
		'port'		 => $key['mail']['port'],
		'protocol' 	 => $key['mail']['protocol'],
	],
	'views_path' 		=> dirname(__DIR__) . '/resources/views/',
	'Twig_Extension' 	=> [
		get(\Core\Extension\TwigExtensionRouter::class),
		get(\Twig\Extension\DebugExtension::class),
		get(\Core\Extension\TwigExtensionAssets::class),
		get(\Core\Extension\TwigExtensionMessagueFlash::class),
		get(\Core\Extension\TwigExtensionSession::class),
		get(\Core\Extension\TwigExtensionCsrf::class)
	],
	RouteCollectionInterface::class => factory(RouterFactory::class),
	RendererInterface::class 		=> factory(RenderFactory::class),
	MailerInterface::class 			=> factory(MailerFactory::class),
	LogInterface::class 			=> factory(LogFactory::class),
	SessionInterface::class 		=> create(Session::class),
	EncryptInterface::class 		=> factory(EncryptFactory::class),
	ValidateInterface::class 		=> create(Validate::class),
	AccessInterface::class 			=> create(Access::class)->constructor(get(SessionInterface::class), get(LogInterface::class), get(ValidateInterface::class), get(EncryptInterface::class)),
	Capcha::class 					=> autowire()->constructor(get('secret_capcha')),
	//MIddlewares
	\Core\Middleware\pipe\SlashesMiddleware::class 		 => autowire(),
	\Core\Middleware\pipe\RouterMiddleware::class 	     => autowire(),
	\Core\Middleware\pipe\HttpMiddleware::class 		 => autowire(),
	\Core\Middleware\pipe\CsrfMiddleware::class 		 => autowire()->constructor(get(SessionInterface::class)),
	\Core\Middleware\pipe\VerifySessionMiddleware::class => autowire()->constructor(get(SessionInterface::class), get('session_key')),
	\Core\Middleware\pipe\InactivityMiddleware::class 	 => autowire()->constructor(get(SessionInterface::class)),
];
