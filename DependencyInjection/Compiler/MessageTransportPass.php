<?php

namespace PE\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MessageTransportPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $transports = [];
        $serviceIDs = $container->findTaggedServiceIds('pe_user.message_transport');

        foreach ($serviceIDs as $serviceID => $tags) {
            $transports[] = new Reference($serviceID);
        }

        $container->setParameter('pe_user.message_transports', $transports);
    }
}
