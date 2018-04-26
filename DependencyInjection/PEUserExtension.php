<?php

namespace PE\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class PEUserExtension extends Extension
{
    /**
     * @var array
     */
    private static $drivers = [
        'orm' => [
            'registry' => 'doctrine',
        ],
        'mongodb' => [
            'registry' => 'doctrine_mongodb',
        ],
    ];

    /**
     * @inheritDoc
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        if ('custom' !== $config['driver']) {
            if (!isset(self::$drivers[$config['driver']])) {
                throw new \RuntimeException('Unknown driver');
            }

            // Set registry alias
            $container->setAlias(
                'pe_user.doctrine_registry',
                new Alias(self::$drivers[$config['driver']]['registry'], false)
            );

            // Set factory to object manager
            $definition = $container->getDefinition('pe_user.object_manager');
            $definition->setFactory([new Reference('pe_user.doctrine_registry'), 'getManager']);

            // Set manager name to access in config
            $container->setParameter('pe_user.object_manager_name', $config['object_manager_name']);

            // Set parameter for switch mapping
            $container->setParameter('pe_user.backend_type.' . $config['driver'], true);

            // Set classes to use in default services
            $container->setParameter('pe_user.class.user', $config['class']['user']);
            $container->setParameter('pe_user.class.group', $config['class']['group']);
        }

        $container->setParameter('pe_user.check_access', $config['check_access']);

        // Set aliases to services
        $container->setAlias('pe_user.repository.user', new Alias($config['service']['repository_user'], true));
        $container->setAlias('pe_user.repository.group', new Alias($config['service']['repository_group'], true));
        $container->setAlias('pe_user.manager', new Alias($config['service']['manager'], true));

        // Set default senders
        $container->setParameter('pe_user.sender.email', $config['sender']['email']);
        $container->setParameter('pe_user.sender.phone', $config['sender']['phone']);

        if (!empty($config['change_password'])) {
            $this->loadChangePassword($config['change_password'], $container, $loader);
        }

        if (!empty($config['invitation'])) {
            $this->loadInvitation($config['invitation'], $container, $loader);
        }

        if (!empty($config['profile'])) {
            $this->loadProfile($config['profile'], $container, $loader);
        }

        if (!empty($config['registration'])) {
            $this->loadRegistration($config['registration'], $container, $loader);
        }

        if (!empty($config['reset_password'])) {
            $this->loadResetPassword($config['reset_password'], $container, $loader);
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     *
     * @throws \Exception
     */
    private function loadChangePassword(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/change_password.yaml');
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     *
     * @throws \Exception
     */
    private function loadInvitation(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/invitation.yaml');

        $container->setParameter('pe_user.message.invitation', $config['message']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     *
     * @throws \Exception
     */
    private function loadProfile(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/profile.yaml');
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     *
     * @throws \Exception
     */
    private function loadRegistration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/registration.yaml');

        $container->setParameter('pe_user.message.registration', $config['message']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     *
     * @throws \Exception
     */
    private function loadResetPassword(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/reset_password.yaml');

        $container->setParameter('pe_user.message.reset_password', $config['message']);
    }
}