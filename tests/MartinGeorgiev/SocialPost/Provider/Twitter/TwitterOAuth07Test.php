<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\SocialNetwork;
use MartinGeorgiev\SocialPost\Provider\Twitter\TwitterOAuth07;
use PHPUnit_Framework_TestCase;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 * 
 * @covers MartinGeorgiev\SocialPost\Provider\Twitter\TwitterOAuth07
 */
class TwitterOAuth07Test extends PHPUnit_Framework_TestCase
{
    public function test_can_publish_only_twitter_intended_messages()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([SocialNetwork::TWITTER]);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->canPublish($message));
    }

    public function test_cannot_publish_when_message_not_intended_for_twitter()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([SocialNetwork::FACEBOOK]);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertFalse($twitterProvider->canPublish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher
     */
    public function test_will_throw_an_exception_when_publishing_if_message_is_not_intended_for_twitter()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test message';
        $message = new Message($tweet);
        $message->setNetworksToPublishOn([SocialNetwork::FACEBOOK]);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $twitterProvider->publish($message);
    }
    
    public function test_can_successfully_publish_a_tweet()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $message = new Message($tweet);
        
        $endpoint = 'statuses/update';
        $data = ['status' => $tweet, 'trim_user' => true];
        $twitterResponse = (object)['id_str' => '2007'];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->publish($message));
    }

    public function test_can_successfully_publish_a_tweet_with_a_link()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $link = 'https://www.example.com';
        $message = new Message($tweet, $link);
        
        $endpoint = 'statuses/update';
        $status = $tweet . ' ' . $link;
        $data = ['status' => $status, 'trim_user' => true];
        $twitterResponse = (object)['id_str' => '2007'];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->publish($message));
    }

    public function test_will_fail_if_cannot_find_the_id_of_the_new_tweet()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $tweet = 'test tweet';
        $message = new Message($tweet);
        
        $endpoint = 'statuses/update';
        $data = ['status' => $tweet, 'trim_user' => true];
        $twitterResponse = (object)['id_str' => ''];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertFalse($twitterProvider->publish($message));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage
     */
    public function test_will_throw_an_exception_if_completly_fails_to_publish()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $tweet = 'test tweet';
        $message = new Message($tweet);
        
        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $twitterProvider->publish($message);
    }
}
