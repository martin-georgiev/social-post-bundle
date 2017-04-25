<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('social_post');

        $rootNode
            ->children()
                ->arrayNode('publish_on')
                    ->requiresAtLeastOneElement()
                    ->prototype('enum')
                        ->values(['facebook', 'linkedin', 'twitter'])
                    ->end()
                ->end()
            ->end();

        $providers = $rootNode->children()->arrayNode('providers');
        $this->addFacebook($providers);
        $this->addLinkedIn($providers);
        $this->addTwitter($providers);
        
        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addFacebook(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('facebook')
                    ->children()
                        ->scalarNode('app_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('app_secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('default_access_token')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('page_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('enable_beta_mode')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('default_graph_version')
                            ->defaultNull()
                        ->end()
                        ->enumNode('persistent_data_handler')
                            ->values(['session', 'memory'])
                            ->defaultValue('memory')
                        ->end()
                        ->enumNode('pseudo_random_string_generator')
                            ->values(['mcrypt', 'openssl', 'urandom'])
                            ->defaultValue('openssl')
                        ->end()
                        ->enumNode('http_client_handler')
                            ->values(['curl', 'stream', 'guzzle'])
                            ->defaultValue('curl')
                        ->end()
                    ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addLinkedIn(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('linkedin')
                    ->children()
                        ->scalarNode('client_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('client_secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('company_page_id')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addTwitter(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('twitter')
                    ->children()
                        ->scalarNode('consumer_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('consumer_secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('access_token')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('access_token_secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
            ->end();
    }
}
