<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPostBundle;

use MartinGeorgiev\SocialPostBundle\DependencyInjection\Compiler\AllInOnePass;
use MartinGeorgiev\SocialPostBundle\SocialPostBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @since 1.0.0
 *
 * @license https://opensource.org/licenses/MIT
 *
 * @see https://github.com/martin-georgiev/social-post-bundle
 */
class SocialPostBundleTest extends TestCase
{
    /**
     * @test
     */
    public function will_add_compiler_class_for_the_main_all_in_one_service(): void
    {
        $compilerPass = new AllInOnePass();
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder
            ->expects($this->once())
            ->method('addCompilerPass')
            ->with($compilerPass);

        $bundle = new SocialPostBundle();
        $bundle->build($containerBuilder);
    }
}
