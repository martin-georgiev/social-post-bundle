<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPostBundle\DependencyInjection\Compiler;

use MartinGeorgiev\SocialPost\Publisher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @since 1.0.0
 *
 * @license https://opensource.org/licenses/MIT
 *
 * @see https://github.com/martin-georgiev/social-post-bundle
 */
class AllInOnePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('social_post');
        $publishOn = $container->getParameter('social_post.configuration.publish_on');
        foreach ($publishOn as $provider) {
            $serviceName = 'social_post.'.$provider;
            if (!$container->has($serviceName)) {
                throw new OutOfBoundsException(\sprintf('Cannot find service %s when injecting dependencies for "social_post"', $serviceName));
            }
            if (!$container->get($serviceName) instanceof Publisher) {
                throw new InvalidArgumentException(\sprintf('Service %s should be an instance of %s', $serviceName, Publisher::class));
            }
            $definition->addArgument(new Reference($serviceName));
        }
    }
}
