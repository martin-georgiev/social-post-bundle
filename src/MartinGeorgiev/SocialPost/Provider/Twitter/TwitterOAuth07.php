<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingSocialPost;
use MartinGeorgiev\SocialPost\Provider\SocialNetworkPublisher;
use Throwable;

/**
 * Provider for publishing on a Twitter page.
 * Uses TwitterOAuth v0.7
 * @see https://github.com/abraham/twitteroauth
 *
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class TwitterOAuth07 implements SocialNetworkPublisher
{
    /**
     * @var TwitterOAuth
     */
    private $twitter;

    /**
     * @param TwitterOAuth $twitter Ready to use instance of TwitterOAuth
     */
    public function __construct(TwitterOAuth $twitter)
    {
        $this->twitter = $twitter;
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
            $post = $this->twitter->post('statuses/update', ['status' => $message, 'trim_user' => true]);

            return !empty($post->id_str);
        } catch (Throwable $t) {
            throw new FailureWhenPublishingSocialPost($t);
        }
    }
}
