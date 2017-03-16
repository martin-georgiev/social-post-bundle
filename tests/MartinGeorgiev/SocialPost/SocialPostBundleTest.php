<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost;

use MartinGeorgiev\SocialPost\DependencyInjection\Compiler\AllInOnePass;
use MartinGeorgiev\SocialPost\SocialPostBundle;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 * 
 * @covers MartinGeorgiev\SocialPost\SocialPostBundle
 */
class SocialPostBundleTest extends PHPUnit_Framework_TestCase
{
    public function test_will_add_compiler_class_for_the_main_AllInOne_service()
    {
        $containerBuilder = $this
            ->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['addCompilerPass'])
            ->getMock();

        $compilerPass = new AllInOnePass();
        $containerBuilder
            ->expects($this->once())
            ->method('addCompilerPass')
            ->with($compilerPass);

        $bundle = new SocialPostBundle();
        $bundle->build($containerBuilder);
    }
}
