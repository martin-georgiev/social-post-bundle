<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingSocialPost;
use MartinGeorgiev\SocialPost\Provider\Message;
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
    public function publish(Message $message): bool
    {
        try {
            $status = $this->prepareStatus($message);
            $post = $this->twitter->post('statuses/update', ['status' => $status, 'trim_user' => true]);

            return !empty($post->id_str);
        } catch (Throwable $t) {
            throw new FailureWhenPublishingSocialPost($t);
        }
    }

    /**
     * @param Message $message
     * @return string
     */
    protected function prepareStatus(Message $message): string
    {
        $status = $message->getMessage();

        if (filter_var($message->getLink(), FILTER_VALIDATE_URL) !== false) {
            $linkIsNotIncludedInTheStatus = mb_strpos($status, $message->getLink()) === false;
            if ($linkIsNotIncludedInTheStatus) {
                $status .= ' ' . $message->getLink();
            }
        }

        return $status;
    }
}
