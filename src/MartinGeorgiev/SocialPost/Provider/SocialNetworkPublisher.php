<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

/**
 * Main contract for publishing a new public message at a social network account
 *
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
interface SocialNetworkPublisher
{
    /**
     * @param string $message The message to post
     * @param string $link Optional link to a webpage to display along the message
     * @param string $pictureLink Optional address of a picture to display along the message
     * @param string $caption Optional caption to display along the message
     * @param string $description Optional description to display along the message
     * @return bool
     * @throws FailureWhenPublishingSocialPost
     */
    public function publish(
        string $message,
        string $link = '',
        string $pictureLink = '',
        string $caption = '',
        string $description = ''
    ): bool;
}
