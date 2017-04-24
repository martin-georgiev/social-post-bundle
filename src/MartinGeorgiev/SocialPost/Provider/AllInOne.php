<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage;
use Throwable;

/**
 * Publish simultaneously to all configured social networks
 *
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class AllInOne implements SocialNetworkPublisher
{
    /**
     * @var array
     */
    private $publishers = [];

    /**
     * @param SocialNetworkPublisher ...$publishers List of instantiated SocialNetworkPublisher's
     */
    public function __construct(SocialNetworkPublisher ...$publishers)
    {
        foreach ($publishers as $publisher) {
            $this->publishers[] = $publisher;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canPublish(Message $message): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Message $message): bool
    {
        try {
            $allPublished = (int)true;
            foreach ($this->publishers as $publisher) {
                if ($publisher->canPublish($message)) {
                    $allPublished &= $publisher->publish($message);
                }
            }

            return (bool)$allPublished;
        } catch (Throwable $t) {
            throw new FailureWhenPublishingMessage($t);
        }
    }
}
