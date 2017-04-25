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
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('social_post.configuration.publish_on', $configuration['publish_on']);

        $this->setFacebookParameters($configuration, $container);
        $this->setLinkedInParameters($configuration, $container);
        $this->setTwitterParameters($configuration, $container);
    }

    /**
     * @param array $configuration
     * @param ContainerBuilder $container
     * @throws InvalidConfigurationException
     */
    private function setFacebookParameters(array $configuration, ContainerBuilder $container)
    {
        if (!in_array('facebook', $configuration['publish_on'])) {
            return;
        }

        if (!isset($configuration['providers']['facebook'])) {
            throw new InvalidConfigurationException('Found no configuration for the Facebook provider');
        }

        $facebookConfiguration = $configuration['providers']['facebook'];
        $container->setParameter('social_post.configuration.facebook', $facebookConfiguration);
        $container->setParameter('social_post.configuration.facebook.page_id', $facebookConfiguration['page_id']);
    }

    /**
     * @param array $configuration
     * @param ContainerBuilder $container
     * @throws InvalidConfigurationException
     */
    private function setLinkedInParameters(array $configuration, ContainerBuilder $container)
    {
        if (!in_array('linkedin', $configuration['publish_on'])) {
            return;
        }

        if (!isset($configuration['providers']['linkedin'])) {
            throw new InvalidConfigurationException('Found no configuration for the LinkedIn provider');
        }

        $linkedinConfiguration = $configuration['providers']['linkedin'];
        $linkedinParameters = ['client_id', 'client_secret', 'company_page_id'];
        foreach ($linkedinParameters as $parameter) {
            $container->setParameter('social_post.configuration.linkedin.' . $parameter, $linkedinConfiguration[$parameter]);
        }
    }

    /**
     * @param array $configuration
     * @param ContainerBuilder $container
     * @throws InvalidConfigurationException
     */
    private function setTwitterParameters(array $configuration, ContainerBuilder $container)
    {
        if (!in_array('twitter', $configuration['publish_on'])) {
            return;
        }

        if (!isset($configuration['providers']['twitter'])) {
            throw new InvalidConfigurationException('Found no configuration for the Twitter provider');
        }

        $twitterConfiguration = $configuration['providers']['twitter'];
        $twitterParameters = ['consumer_key', 'consumer_secret', 'access_token', 'access_token_secret'];
        foreach ($twitterParameters as $parameter) {
            $container->setParameter('social_post.configuration.twitter.' . $parameter, $twitterConfiguration[$parameter]);
        }
    }
}
