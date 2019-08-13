<?php
namespace Core\Mail;

use Psr\Container\ContainerInterface;
use Core\Log\LogInterface;

/**
 * Factory de mailer usando la libreria de Swifmailer
 */
class MailerFactory
{
    /**
     * [__invoke description]
     * @param  ContainerInterface $container [Contenedor PHP-DI]
     * @return [Mailer]                      [Clase para enviar correos electronicos]
     */
    public function __invoke(ContainerInterface $container): Mailer
    {
        $options = $container->get('MailerOptions');
        $log = $container->get(LogInterface::class);

        return new Mailer($options['host'], $options['port'], $options['protocol'], $options['user'], $options['pass'], $log);
    }
}
