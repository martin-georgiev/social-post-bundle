<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider\Facebook;

use Facebook\Facebook;
use Facebook\FacebookResponse;
use MartinGeorgiev\SocialPost\Provider\Facebook\SDK5;
use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\SocialNetwork;
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
    public function test_can_publish_only_facebook_intended_messages()
    {
        $pageId = '2009';
        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();
        
        $statusUpdate = 'test message';
        $message = new Message($statusUpdate);
        $message->setNetworksToPublishOn([SocialNetwork::FACEBOOK]);

        $facebookProvider = new SDK5($facebook, $pageId);
        $this->assertTrue($facebookProvider->canPublish($message));
    }

    public function test_cannot_publish_when_message_not_intended_for_facebook()
    {
        $pageId = '2009';
        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $statusUpdate = 'test message';
        $message = new Message($statusUpdate);
        $message->setNetworksToPublishOn([SocialNetwork::TWITTER]);

        $facebookProvider = new SDK5($facebook, $pageId);
        $this->assertFalse($facebookProvider->canPublish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher
     */
    public function test_will_throw_an_exception_when_publishing_if_message_is_not_intended_for_facebook()
    {
        $pageId = '2009';
        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([SocialNetwork::TWITTER]);

        $facebookProvider = new SDK5($facebook, $pageId);
        $facebookProvider->publish($message);
    }
    
    public function test_can_successfully_publish_as_a_page()
    {
        $pageId = '2009';
        $endpoint = sprintf('/%s/feed', $pageId);

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

        $statusUpdate = 'test status update';
        $link = 'https://www.example.com';
        $pictureLink = 'https://www.example.com/logo.svg';
        $caption = 'some caption';
        $description = 'some description';
        $message = new Message($statusUpdate, $link, $pictureLink, $caption, $description);
        $data = ['message' => $statusUpdate, 'link' => $link, 'picture' => $pictureLink, 'caption' => $caption, 'description' => $description];
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
        $pageId = '2009';
        $endpoint = sprintf('/%s/feed', $pageId);
        
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
     * @expectedException \MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage
     */
    public function test_will_throw_an_exception_if_completly_fails_to_publish()
    {
        $pageId = '2009';
        $facebook = $this
            ->getMockBuilder(Facebook::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();
        
        $statusUpdate = 'test status update';
        $message = new Message($statusUpdate);

        $facebookProvider = new SDK5($facebook, $pageId);
        $facebookProvider->publish($message);
    }
}
