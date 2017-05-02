<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider;

use Exception;
use MartinGeorgiev\SocialPost\Provider\AllInOne;
use MartinGeorgiev\SocialPost\Provider\Facebook\SDK5;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\Twitter\TwitterOAuth07;
use PHPUnit_Framework_TestCase;

/**
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 * 
 * @covers MartinGeorgiev\SocialPost\Provider\AllInOne
 */
class AllInOneTest extends PHPUnit_Framework_TestCase
{
    public function test_can_publish_any_message()
    {
        $socialPost = 'test message';
        $message = new Message($socialPost);

        $facebook = $this
            ->getMockBuilder(SDK5::class)
            ->disableOriginalConstructor()
            ->getMock();

        $allInOne = new AllInOne($facebook);
        $this->assertTrue($allInOne->canPublish($message));
    }

    public function test_can_successfully_publish_to_all_providers()
    {
        $socialPost = 'test message';
        $message = new Message($socialPost);

        $facebook = $this
            ->getMockBuilder(SDK5::class)
            ->disableOriginalConstructor()
            ->setMethods(['publish'])
            ->getMock();
        $facebook
            ->expects($this->once())
            ->method('publish')
            ->with($message)
            ->willReturn(true);

        $twitter = $this
            ->getMockBuilder(TwitterOAuth07::class)
            ->disableOriginalConstructor()
            ->setMethods(['publish'])
            ->getMock();
        $twitter
            ->expects($this->once())
            ->method('publish')
            ->with($message)
            ->willReturn(true);

        $allInOne = new AllInOne($facebook, $twitter);
        $this->assertTrue($allInOne->publish($message));
    }

    public function test_will_fail_if_cannot_successfully_publish_to_all_providers()
    {
        $socialPost = 'test message';
        $message = new Message($socialPost);
        
        $facebook = $this
            ->getMockBuilder(SDK5::class)
            ->disableOriginalConstructor()
            ->setMethods(['publish'])
            ->getMock();
        $facebook
            ->expects($this->once())
            ->method('publish')
            ->with($message)
            ->willReturn(true);

        $twitter = $this
            ->getMockBuilder(TwitterOAuth07::class)
            ->disableOriginalConstructor()
            ->setMethods(['publish'])
            ->getMock();
        $twitter
            ->expects($this->once())
            ->method('publish')
            ->with($message)
            ->willReturn(false);

        $allInOne = new AllInOne($facebook, $twitter);
        $this->assertFalse($allInOne->publish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage
     */
    public function test_will_throw_an_exception_if_completly_fails_to_publish()
    {
        $socialPost = 'test message';
        $message = new Message($socialPost);

        $facebook = $this
            ->getMockBuilder(SDK5::class)
            ->disableOriginalConstructor()
            ->setMethods(['publish'])
            ->getMock();
        $facebook
            ->expects($this->once())
            ->method('publish')
            ->with($message)
            ->willReturn(true);

        $exception = new FailureWhenPublishingMessage(new Exception('test exception'));
        $twitter = $this
            ->getMockBuilder(TwitterOAuth07::class)
            ->disableOriginalConstructor()
            ->setMethods(['publish'])
            ->getMock();
        $twitter
            ->expects($this->once())
            ->method('publish')
            ->with($message)
            ->willThrowException($exception);

        $allInOne = new AllInOne($facebook, $twitter);
        $allInOne->publish($message);
    }
}
