<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class SocialPostExtension extends Extension
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var YamlFileLoader
     */
    private $loader;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;
        $this->loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/service'));
        $this->configuration = $this->processConfiguration(new Configuration(), $configs);
        
        $this->container->setParameter('social_post.configuration.publish_on', $this->configuration['publish_on']);

        $this->setFacebookParameters();
        $this->setLinkedInParameters();
        $this->setTwitterParameters();
        
        $this->loader->load('all_in_one.yml');
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function setFacebookParameters()
    {
        $configuration = $this->configuration;
        
        if (!in_array('facebook', $configuration['publish_on'])) {
            return;
        }

        if (!isset($configuration['providers']['facebook'])) {
            throw new InvalidConfigurationException('Found no configuration for the Facebook provider');
        }

        $facebookConfiguration = $configuration['providers']['facebook'];
        $this->container->setParameter('social_post.configuration.facebook', $facebookConfiguration);
        $this->container->setParameter('social_post.configuration.facebook.page_id', $facebookConfiguration['page_id']);

        $this->loader->load('facebook.yml');
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function setLinkedInParameters()
    {
        $configuration = $this->configuration;
        
        if (!in_array('linkedin', $configuration['publish_on'])) {
            return;
        }

        if (!isset($configuration['providers']['linkedin'])) {
            throw new InvalidConfigurationException('Found no configuration for the LinkedIn provider');
        }

        $linkedinConfiguration = $configuration['providers']['linkedin'];
        $linkedinParameters = ['client_id', 'client_secret', 'access_token', 'company_page_id'];
        foreach ($linkedinParameters as $parameter) {
            $this->container->setParameter('social_post.configuration.linkedin.' . $parameter, $linkedinConfiguration[$parameter]);
        }

        $this->loader->load('linkedin.yml');
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function setTwitterParameters()
    {
        $configuration = $this->configuration;
        
        if (!in_array('twitter', $configuration['publish_on'])) {
            return;
        }

        if (!isset($configuration['providers']['twitter'])) {
            throw new InvalidConfigurationException('Found no configuration for the Twitter provider');
        }

        $twitterConfiguration = $configuration['providers']['twitter'];
        $twitterParameters = ['consumer_key', 'consumer_secret', 'access_token', 'access_token_secret'];
        foreach ($twitterParameters as $parameter) {
            $this->container->setParameter('social_post.configuration.twitter.' . $parameter, $twitterConfiguration[$parameter]);
        }

        $this->loader->load('twitter.yml');
    }
}
