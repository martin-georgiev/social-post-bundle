<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider\Facebook\FacebookOAuth07;

use Facebook\Facebook;
use Facebook\FacebookResponse;
use MartinGeorgiev\SocialPost\Provider\Facebook\SDK5;
use MartinGeorgiev\SocialPost\Provider\Message;
use PHPUnit_Framework_TestCase;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 * 
 * @covers MartinGeorgiev\SocialPost\Provider\Facebook\SDK5
 */
class SDK5Test extends PHPUnit_Framework_TestCase
{
    public function test_can_successfully_publish_as_a_page()
    {
        $facebookResponse = $this
            ->getMockBuilder(FacebookResponse::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGraphNode'])
            ->getMock();
        
        $post = ['id' => '2013'];
        $facebookResponse
            ->expects($this->once())
            ->method('getGraphNode')
            ->willReturn($post);

        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $pageId = '2009';
        $endpoint = sprintf('/%s/feed', $pageId);
        $statusUpdate = 'test status update';
        $message = new Message($statusUpdate);
        $data = ['message' => $statusUpdate];
        $facebook
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($facebookResponse);

        $facebookProvider = new SDK5($facebook, $pageId);
        $this->assertTrue($facebookProvider->publish($message));
    }

    public function test_will_fail_if_cannot_find_the_id_of_the_new_post()
    {
        $facebookResponse = $this
            ->getMockBuilder(FacebookResponse::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGraphNode'])
            ->getMock();

        $post = ['id' => ''];
        $facebookResponse
            ->expects($this->once())
            ->method('getGraphNode')
            ->willReturn($post);

        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $pageId = '2009';
        $endpoint = sprintf('/%s/feed', $pageId);
        $statusUpdate = 'test status update';
        $message = new Message($statusUpdate);
        $data = ['message' => $statusUpdate];
        $facebook
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($facebookResponse);

        $facebookProvider = new SDK5($facebook, $pageId);
        $this->assertFalse($facebookProvider->publish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingSocialPost
     */
    public function test_will_throw_an_exception_if_completly_fails_to_publish()
    {
        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $pageId = '2009';
        $statusUpdate = 'test status update';
        $message = new Message($statusUpdate);

        $facebookProvider = new SDK5($facebook, $pageId);
        $facebookProvider->publish($message);
    }
}
