<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

/**
 * Representation of a public message (status update) for a social network
 *
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class Message
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
     * @var array
     * @see SocialNetwork::ANY List of available networks
     */
    private $networksToPublishOn = [SocialNetwork::ANY];

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
     * @return string
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

    /**
     * @return Message
     */
    public function setNetworksToPublishOn(array $networksToPublishOn): Message
    {
        $this->networksToPublishOn = $networksToPublishOn;
        
        return $this;
    }

    /**
     * @return array
     */
    public function getNetworksToPublishOn(): array
    {
        return $this->networksToPublishOn;
    }
}
