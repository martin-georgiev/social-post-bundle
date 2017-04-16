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
     * @param Message $message
     * @return bool
     * @throws FailureWhenPublishingSocialPost
     */
    public function publish(Message $message): bool;
}
