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
     * Tests if a message can be published with a network.
     *
     * @param Message $message
     * @return bool
     */
    public function canPublish(Message $message): bool;

    /**
     * Publishes a message to a network.
     *
     * @param Message $message
     * @return bool
     * @throws MessageNotIntendedForPublisher
     * @throws FailureWhenPublishingMessage
     */
    public function publish(Message $message): bool;
}
