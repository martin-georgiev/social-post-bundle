<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

use InvalidArgumentException;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingSocialPost;
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
     * @param SocialNetworkPublisher[] $publishers List of instantiated SocialNetworkPublisher's
     */
    public function __construct(...$publishers)
    {
        foreach ($publishers as $publisher) {
            if (!($publisher instanceof SocialNetworkPublisher)) {
                $message = sprintf('At least one of the given publishers is not implementing %s', SocialNetworkPublisher::class);
                throw new InvalidArgumentException($message);
            }

            $this->publishers[] = $publisher;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function publish(
        string $message,
        string $link = '',
        string $pictureLink = '',
        string $caption = '',
        string $description = ''
    ): bool {
        try {
            $allPublished = (int)true;
            foreach ($this->publishers as $publisher) {
                $allPublished &= $publisher->publish($message, $link, $pictureLink, $caption, $description);
            }

            return (bool)$allPublished;
        } catch (Throwable $t) {
            throw new FailureWhenPublishingSocialPost($t);
        }
    }
}
