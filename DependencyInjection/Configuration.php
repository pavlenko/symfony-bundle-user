<?php

namespace PE\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('pe_user');

        $drivers = ['orm', 'mongodb', 'custom'];

        $rootNode
            ->children()
                ->scalarNode('driver')
                    ->validate()
                        ->ifNotInArray($drivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of ' . json_encode($drivers))
                    ->end()
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('check_access')->defaultFalse()->end()
                ->scalarNode('object_manager_name')->defaultNull()->end()
                ->arrayNode('class')
                    ->isRequired()
                    ->children()
                        ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('group')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('sender')
                    ->isRequired()
                    ->children()
                        ->scalarNode('email')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('phone')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('manager')->defaultValue('pe_user.manager.default')->end()
                        ->scalarNode('repository_user')->defaultValue('pe_user.repository.user.default')->end()
                        ->scalarNode('repository_group')->defaultValue('pe_user.repository.group.default')->end()
                    ->end()
                ->end()
            ->end()

            ->validate()
                ->ifTrue(function ($v) {
                    return 'custom' === $v['driver']
                        && (
                            'pe_user.repository.user.default' === $v['service']['repository_user']
                        );
                })
                ->thenInvalid('You need to specify your own services when using the "custom" driver.')
            ->end()
        ;

        $this->addChangePasswordSection($rootNode);
        $this->addInvitationSection($rootNode);
        $this->addProfileSection($rootNode);
        $this->addRegistrationSection($rootNode);
        $this->addResetPasswordSection($rootNode);

        return $treeBuilder;
    }

    private function addChangePasswordSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('change_password')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()->end()
                ->end()
            ->end()
        ;
    }

    private function addInvitationSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('invitation')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('message')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->append($this->createMessageChildren('email', '%pe_user.sender.email%', '@PEUser/Registration/message_email.txt.twig'))
                                ->append($this->createMessageChildren('phone', '%pe_user.sender.phone%', '@PEUser/Registration/message_phone.txt.twig'))
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addProfileSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('profile')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()->end()
                ->end()
            ->end()
        ;
    }

    private function addRegistrationSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('registration')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->booleanNode('confirmation')->defaultTrue()->end()
                        ->arrayNode('message')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->append($this->createMessageChildren('email', '%pe_user.sender.email%', '@PEUser/Registration/message_email.txt.twig'))
                                ->append($this->createMessageChildren('phone', '%pe_user.sender.phone%', '@PEUser/Registration/message_phone.txt.twig'))
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addResetPasswordSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('reset_password')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('message')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->append($this->createMessageChildren('email', '%pe_user.sender.email%', '@PEUser/ResetPassword/message_email.txt.twig'))
                                ->append($this->createMessageChildren('phone', '%pe_user.sender.phone%', '@PEUser/ResetPassword/message_phone.txt.twig'))
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function createMessageChildren($type, $sender, $template): ArrayNodeDefinition
    {
        return (new ArrayNodeDefinition($type))
            ->addDefaultsIfNotSet()
            ->children()
                 ->scalarNode('sender')->defaultValue($sender)->end()
                 ->scalarNode('template')->defaultValue($template)->end()
             ->end()
        ;
    }
}