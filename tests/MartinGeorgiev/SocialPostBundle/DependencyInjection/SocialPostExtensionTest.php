<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPostBundle\DependencyInjection;

use MartinGeorgiev\SocialPostBundle\DependencyInjection\SocialPostExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * @since 1.0.0
 *
 * @license https://opensource.org/licenses/MITs
 *
 * @see https://github.com/martin-georgiev/social-post-bundle
 */
class SocialPostExtensionTest extends TestCase
{
    private function getConfigurationWithEmptyPublishOn(): array
    {
        $yaml = <<<'EOF'
social_post:
    publish_on: []
EOF;

        return (new Parser())->parse($yaml);
    }

    private function getConfigurationWithEmptyFacebookProvider(): array
    {
        $yaml = <<<'EOF'
social_post:
    publish_on: [facebook]
EOF;

        return (new Parser())->parse($yaml);
    }

    private function getConfigurationWithEmptyLinkedInProvider(): array
    {
        $yaml = <<<'EOF'
social_post:
    publish_on: [linkedin]
EOF;

        return (new Parser())->parse($yaml);
    }

    private function getConfigurationWithEmptyTwitterProvider(): array
    {
        $yaml = <<<'EOF'
social_post:
    publish_on: [twitter]
EOF;

        return (new Parser())->parse($yaml);
    }

    private function getMinimalConfiguration(): array
    {
        $yaml = <<<'EOF'
social_post:
    publish_on: [facebook, linkedin, twitter]
    providers:
        facebook:
            app_id: "2017"
            app_secret: "some-secret"
            default_access_token: "some-access-token"
            page_id: "681"
        linkedin:
            client_id: "2017"
            client_secret: "some-secret"
            access_token: "some-access-token"
            company_page_id: "1878"
        twitter:
            consumer_key: "some-consumer-key"
            consumer_secret: "some-consumer-secret"
            access_token: "some-access-token"
            access_token_secret: "some-access-token-secret"
EOF;

        return (new Parser())->parse($yaml);
    }

    private function getCompleteConfiguration(): array
    {
        $yaml = <<<'EOF'
social_post:
    publish_on: [facebook, linkedin, twitter]
    providers:
        facebook:
            app_id: "2017"
            app_secret: "some-secret"
            default_access_token: "some-access-token"
            page_id: "681"
            enable_beta_mode: true
            default_graph_version: "v2.8"
            persistent_data_handler: "session"
            pseudo_random_string_generator: "mcrypt"
            http_client_handler: "guzzle"
        linkedin:
            client_id: "2017"
            client_secret: "some-secret"
            access_token: "some-access-token"
            company_page_id: "1878"
        twitter:
            consumer_key: "some-consumer-key"
            consumer_secret: "some-consumer-secret"
            access_token: "some-access-token"
            access_token_secret: "some-access-token-secret"
EOF;

        return (new Parser())->parse($yaml);
    }

    /**
     * @param mixed $expectedParameterValue
     */
    private function assertContainerParameter($expectedParameterValue, string $containerParameter, ContainerBuilder $containerBuilder): void
    {
        $this->assertSame(
            $expectedParameterValue,
            $containerBuilder->getParameter($containerParameter),
            \sprintf('%s parameter is correct', $containerParameter)
        );
    }

    /**
     * @test
     */
    public function will_throw_an_exception_when_no_value_for_publish_on(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $extension = new SocialPostExtension();
        $extension->load($this->getConfigurationWithEmptyPublishOn(), new ContainerBuilder());
    }

    /**
     * @test
     */
    public function will_throw_an_exception_when_no_facebook_provider_is_given(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $extension = new SocialPostExtension();
        $extension->load($this->getConfigurationWithEmptyFacebookProvider(), new ContainerBuilder());
    }

    /**
     * @test
     */
    public function will_throw_an_exception_when_no_linkedin_provider_is_given(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $extension = new SocialPostExtension();
        $extension->load($this->getConfigurationWithEmptyLinkedInProvider(), new ContainerBuilder());
    }

    /**
     * @test
     */
    public function will_throw_an_exception_when_no_twitter_provider_is_given(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $extension = new SocialPostExtension();
        $extension->load($this->getConfigurationWithEmptyTwitterProvider(), new ContainerBuilder());
    }

    /**
     * @test
     */
    public function facebook_defaults_with_minimal_configuration(): void
    {
        $configs = $this->getMinimalConfiguration();
        $containerBuilder = new ContainerBuilder();
        $extension = new SocialPostExtension();
        $extension->load($configs, $containerBuilder);

        $facebookConfigurationWithDefaults = [
            'app_id' => '2017',
            'app_secret' => 'some-secret',
            'default_access_token' => 'some-access-token',
            'page_id' => '681',
            'enable_beta_mode' => false,
            'default_graph_version' => null,
            'persistent_data_handler' => 'memory',
            'pseudo_random_string_generator' => 'openssl',
            'http_client_handler' => 'curl',
        ];

        $this->assertContainerParameter($facebookConfigurationWithDefaults, 'social_post.configuration.facebook', $containerBuilder);
    }

    /**
     * @test
     */
    public function complete_configuration(): void
    {
        $configs = $this->getCompleteConfiguration();
        $containerBuilder = new ContainerBuilder();
        $extension = new SocialPostExtension();
        $extension->load($configs, $containerBuilder);

        $this->assertContainerParameter($configs['social_post']['publish_on'], 'social_post.configuration.publish_on', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['facebook'], 'social_post.configuration.facebook', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['facebook']['page_id'], 'social_post.configuration.facebook.page_id', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['linkedin']['client_id'], 'social_post.configuration.linkedin.client_id', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['linkedin']['client_secret'], 'social_post.configuration.linkedin.client_secret', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['linkedin']['access_token'], 'social_post.configuration.linkedin.access_token', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['linkedin']['company_page_id'], 'social_post.configuration.linkedin.company_page_id', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['twitter']['consumer_key'], 'social_post.configuration.twitter.consumer_key', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['twitter']['consumer_secret'], 'social_post.configuration.twitter.consumer_secret', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['twitter']['access_token'], 'social_post.configuration.twitter.access_token', $containerBuilder);
        $this->assertContainerParameter($configs['social_post']['providers']['twitter']['access_token_secret'], 'social_post.configuration.twitter.access_token_secret', $containerBuilder);
    }
}
