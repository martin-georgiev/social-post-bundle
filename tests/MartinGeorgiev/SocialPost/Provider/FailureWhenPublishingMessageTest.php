<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider;

use Exception;
use DomainException;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage;
use PHPUnit_Framework_TestCase;

/**
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 *
 * @covers MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage
 */
class FailureWhenPublishingMessageTest extends PHPUnit_Framework_TestCase
{
    public function test_is_exception()
    {
        $exception = new Exception('test exception');
        $implementation = new FailureWhenPublishingMessage($exception);
        $this->assertInstanceOf(DomainException::class, $implementation);
    }
}
