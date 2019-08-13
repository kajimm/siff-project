<?php

namespace Core\Encryp;

use Psr\Container\ContainerInterface;
/**
 * Core\Encryp\EncryptFactory
 */
class EncryptFactory
{
	
	function __invoke(ContainerInterface $container): Cryp
	{
		$config = $container->get('crypt');

		return new Cryp($config['secret_key'], $config['secret_iv'], $config['method']);
	}
}