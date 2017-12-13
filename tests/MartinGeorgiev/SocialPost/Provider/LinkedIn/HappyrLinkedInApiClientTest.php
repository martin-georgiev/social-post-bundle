<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider\LinkedIn;

use Happyr\LinkedIn\LinkedIn;
use MartinGeorgiev\SocialPost\Provider\LinkedIn\HappyrLinkedInApiClient;
use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\SocialNetwork;
use PHPUnit\Framework\TestCase;

/**
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 * 
 * @covers MartinGeorgiev\SocialPost\Provider\LinkedIn\HappyrLinkedInApiClient
 */
class HappyrLinkedInApiClientTest extends TestCase
{
    public function test_can_publish_only_linkedin_intended_messages()
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $message->setNetworksToPublishOn([SocialNetwork::LINKEDIN]);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertTrue($linkedInProvider->canPublish($message));
    }

    public function test_cannot_publish_when_message_not_intended_for_linkedin()
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $message->setNetworksToPublishOn([SocialNetwork::FACEBOOK]);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertFalse($linkedInProvider->canPublish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher
     */
    public function test_will_throw_an_exception_when_publishing_if_message_is_not_intended_for_linkedin()
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        $message->setNetworksToPublishOn([SocialNetwork::FACEBOOK]);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $linkedInProvider->publish($message);
    }
    
    public function test_can_successfully_publish_a_share()
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $endpoint = sprintf('v1/companies/%s/shares', $companyPageId);

        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $share = 'test share';
        $link = 'https://www.example.com';
        $pictureLink = 'https://www.example.com/logo.svg';
        $caption = 'some caption';
        $description = 'some description';
        $message = new Message($share, $link, $pictureLink, $caption, $description);

        $data = ['json' => ['comment' => $share, 'visibility' => ['code' => 'anyone'], 'content' => ['submitted-url' => $link, 'submitted-image-url' => $pictureLink, 'title' => $caption, 'description' => $description]]];
        $linkedInResponse = ['updateKey' => '2017'];
        $linkedIn
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($linkedInResponse);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertTrue($linkedInProvider->publish($message));
    }

    public function test_will_fail_if_cannot_find_the_id_of_the_new_share()
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $endpoint = sprintf('v1/companies/%s/shares', $companyPageId);

        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $share = 'test share';
        $message = new Message($share);

        $data = ['json' => ['comment' => $share, 'visibility' => ['code' => 'anyone']]];
        $linkedInResponse = ['updateKey' => ''];
        $linkedIn
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($linkedInResponse);

        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $this->assertFalse($linkedInProvider->publish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage
     */
    public function test_will_throw_an_exception_if_completly_fails_to_publish()
    {
        $accessToken = 'access-token';
        $companyPageId = '2009';
        $linkedIn = $this
            ->getMockBuilder(LinkedIn::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $share = 'test message';
        $message = new Message($share);
        
        $linkedInProvider = new HappyrLinkedInApiClient($linkedIn, $accessToken, $companyPageId);
        $linkedInProvider->publish($message);
    }
}
