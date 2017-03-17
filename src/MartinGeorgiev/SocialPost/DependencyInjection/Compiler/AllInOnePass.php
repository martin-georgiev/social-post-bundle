<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class AllInOnePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('social_post');
        $publishOn = $container->getParameter('social_post.configuration.publish_on');
        foreach ($publishOn as $provider) {
            $serviceName = 'social_post.' . $provider;
            if (!$container->has($serviceName)) {
                throw new OutOfBoundsException('Cannot find service ' . $serviceName . ' when injecting dependecies for "social_post"');
            }
            $definition->addArgument(new Reference($serviceName));
        }
    }
}
