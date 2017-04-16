<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Message\Facebook;

use MartinGeorgiev\SocialPost\Message\Message;

/**
 * Representation of a Facebook's public status update
 *
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class StatusUpdate implements Message
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
     * @var string
     */
    private $pictureLink;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $message The main message
     * @param string $link Optional link to a webpage to display along the message
     * @param string $pictureLink Optional address of a picture to display along the message
     * @param string $caption Optional caption to display along the message
     * @param string $description Optional description to display along the message
     */
    public function __construct(
        string $message,
        string $link = '',
        string $pictureLink = '',
        string $caption = '',
        string $description = ''
    ) {
        $this->message = $message;
        $this->link = $link;
        $this->pictureLink = $pictureLink;
        $this->caption = $caption;
        $this->description = $description;
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

    /**
     * @return string
     */
    public function getPictureLink(): string
    {
        return $this->pictureLink;
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
