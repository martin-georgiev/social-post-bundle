<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Message\Twitter;

use MartinGeorgiev\SocialPost\Message\Message;

/**
 * Representation of a Twitter's tweet
 *
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class Tweet implements Message
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $link;

    /**
     * @param string $message The main message
     * @param string $link Optional link to a webpage to display along the message
     */
    public function __construct(
        string $message,
        string $link = ''
    ) {
        $this->message = $message;
        $this->link = $link;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
