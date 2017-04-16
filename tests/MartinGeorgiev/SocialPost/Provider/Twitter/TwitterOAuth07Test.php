<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider\Twitter\TwitterOAuth07;

use Abraham\TwitterOAuth\TwitterOAuth;
use MartinGeorgiev\SocialPost\Message\Twitter\Tweet;
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
    public function test_can_successfully_publish_a_tweet()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $message = 'test tweet';
        $tweet = new Tweet($message);
        $endpoint = 'statuses/update';
        $data = ['status' => $message, 'trim_user' => true];
        $twitterResponse = (object)['id_str' => '2007'];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->publish($tweet));
    }

    public function test_can_successfully_publish_a_tweet_with_a_link()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $message = 'test tweet';
        $link = 'https://www.example.com';
        $tweet = new Tweet($message, $link);
        $endpoint = 'statuses/update';
        $status = $message . ' ' . $link;
        $data = ['status' => $status, 'trim_user' => true];
        $twitterResponse = (object)['id_str' => '2007'];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertTrue($twitterProvider->publish($tweet));
    }

    public function test_will_fail_if_cannot_find_the_id_of_the_new_tweet()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();

        $message = 'test tweet';
        $tweet = new Tweet($message);
        $endpoint = 'statuses/update';
        $data = ['status' => $message, 'trim_user' => true];
        $twitterResponse = (object)['id_str' => ''];
        $twitterOAuth
            ->expects($this->once())
            ->method('post')
            ->with($endpoint, $data)
            ->willReturn($twitterResponse);

        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $this->assertFalse($twitterProvider->publish($tweet));
    }

    /**
     * @expectedException \MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingSocialPost
     */
    public function test_will_throw_an_exception_if_completly_fails_to_publish()
    {
        $twitterOAuth = $this
            ->getMockBuilder(TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $message = 'test tweet';
        $tweet = new Tweet($message);
        $twitterProvider = new TwitterOAuth07($twitterOAuth);
        $twitterProvider->publish($tweet);
    }
}
