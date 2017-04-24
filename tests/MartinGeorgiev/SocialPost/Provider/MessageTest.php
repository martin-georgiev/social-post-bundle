<?php

declare(strict_types=1);

namespace Tests\MartinGeorgiev\SocialPost\Provider;

use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\SocialNetwork;
use PHPUnit_Framework_TestCase;

/**
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 * 
 * @covers MartinGeorgiev\SocialPost\Provider\Message
 */
class MessageTest extends PHPUnit_Framework_TestCase
{
    public function test_can_build_new_message()
    {
        $contentss = [
            [
                'networks' => [SocialNetwork::FACEBOOK],
                'contents' => ['test message', 'https://www.example.com', 'https://www.example.com/logo.svg', 'test caption', 'test description'],
            ],
            [
                'networks' => [SocialNetwork::ANY],
                'contents' => ['test message', 'https://www.example.com', 'https://www.example.com/logo.svg', 'test caption'],
            ],
            [
                'networks' => [SocialNetwork::TWITTER],
                'contents' => ['test message', 'https://www.example.com', 'https://www.example.com/logo.svg'],
            ],
            [
                'networks' => [SocialNetwork::FACEBOOK, SocialNetwork::TWITTER],
                'contents' => ['test message', 'https://www.example.com'],
            ],
            [
                'networks' => [SocialNetwork::ANY],
                'contents' => ['test message'],
            ],
        ];
        foreach ($contentss as $messageSetup) {
            $contents = $messageSetup['contents'];
            $message = new Message(...$contents);

            $this->assertEquals($contents[0], $message->getMessage());
            if (isset($contents[1])) {
                $this->assertEquals($contents[1], $message->getLink());
            }
            if (isset($contents[2])) {
                $this->assertEquals($contents[2], $message->getPictureLink());
            }
            if (isset($contents[3])) {
                $this->assertEquals($contents[3], $message->getCaption());
            }
            if (isset($contents[4])) {
                $this->assertEquals($contents[4], $message->getDescription());
            }

            $networks = $messageSetup['networks'];
            $this->assertEquals([SocialNetwork::ANY], $message->getNetworksToPublishOn());
            $message->setNetworksToPublishOn($networks);
            $this->assertEquals($networks, $message->getNetworksToPublishOn());
        }
    }
}
