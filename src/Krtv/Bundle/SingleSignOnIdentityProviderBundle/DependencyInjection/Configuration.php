<?php

namespace Krtv\Bundle\SingleSignOnIdentityProviderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Krtv\Bundle\SingleSignOnIdentityProviderBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('krtv_single_sign_on_identity_provider');
        
        $root = method_exists($builder, 'getRootNode')
            ? $builder->getRoorNode()
            : $builder->root('krtv_single_sign_on_identity_provider')
        ;

        $root
            ->children()
                ->scalarNode('host')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function($v) {
                            return preg_match('/^http(s?):\/\//', $v);
                        })
                        ->thenInvalid('SSO host must only contain the host, and not the url scheme, eg: idp.domain.com')
                    ->end()
                ->end()

                ->scalarNode('host_scheme')
                    ->defaultValue('http')
                ->end()

                ->scalarNode('login_path')
                    ->isRequired()
                ->end()

                ->scalarNode('logout_path')
                    ->isRequired()
                ->end()

                ->arrayNode('services')
                    ->info('Array of enabled ServiceProviders (SPs)')
                    ->isRequired()
                    ->prototype('scalar')

                    ->end()
                ->end()

                ->scalarNode('otp_parameter')
                    ->defaultValue('_otp')
                ->end()

                ->scalarNode('secret_parameter')
                    ->defaultValue('secret')
                ->end()

                ->scalarNode('target_path_parameter')
                    ->defaultValue('_target_path')
                ->end()

                ->scalarNode('service_parameter')
                    ->defaultValue('service')
                ->end()

                ->scalarNode('service_extra_parameter')
                    ->defaultValue('service_extra')
                ->end()
            ->end()
        ;

        return $builder;
    }
}
