<?php
namespace Core\Log;

use Psr\Container\ContainerInterface;

/**
 * Factory System log
 */
class LogFactory
{

    public function __invoke(ContainerInterface $container): Log
    {
        
        return new Log();
    }
}
