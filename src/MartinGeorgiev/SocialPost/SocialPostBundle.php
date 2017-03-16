<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost;

use MartinGeorgiev\SocialPost\DependencyInjection\Compiler\AllInOnePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class SocialPostBundle extends BaseBundle
{
    public function build(ContainerBuilder $containerBuilder)
    {
        parent::build($containerBuilder);

        $containerBuilder->addCompilerPass(new AllInOnePass());
    }
}
