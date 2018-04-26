<?php

namespace PE\Bundle\UserBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use PE\Bundle\UserBundle\DependencyInjection\Compiler\MessageTransportPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PEUserBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MessageTransportPass());

        $this->addCompilerMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addCompilerMappingsPass(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => __NAMESPACE__ . '\Model',
        ];

        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver(
                $mappings,
                ['pe_user.model_manager_name'],
                'pe_user.backend_type.orm'
            ));
        }

        if (class_exists('Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass')) {
            $container->addCompilerPass(DoctrineMongoDBMappingsPass::createYamlMappingDriver(
                $mappings,
                ['pe_user.model_manager_name'],
                'pe_user.backend_type.mongodb'
            ));
        }
    }
}