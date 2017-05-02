<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider;

use DomainException;
use MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher;
use PHPUnit_Framework_TestCase;

/**
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 *
 * @covers MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher
 */
class MessageNotIntendedForPublisherTest extends PHPUnit_Framework_TestCase
{
    public function test_is_exception()
    {
        $implementation = new MessageNotIntendedForPublisher('facebook');
        $this->assertInstanceOf(DomainException::class, $implementation);
    }
}
