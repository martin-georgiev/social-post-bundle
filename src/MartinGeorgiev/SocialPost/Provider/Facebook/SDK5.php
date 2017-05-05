<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider\Facebook;

use Facebook\Facebook;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher;
use MartinGeorgiev\SocialPost\Provider\SocialNetwork;
use MartinGeorgiev\SocialPost\Provider\SocialNetworkPublisher;
use Throwable;

/**
 * Provider for publishing on a Facebook page.
 * Uses Facebook PHP SDK v5.
 * @see https://developers.facebook.com/docs/php/Facebook/5.0.0
 *
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class SDK5 implements SocialNetworkPublisher
{
    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var string
     */
    private $pageId;

    /**
     * @param Facebook $facebook Ready to use instance of the Facebook PHP SDK
     * @param string $pageId Identifier of the page, on which the status update will be published
     */
    public function __construct(Facebook $facebook, string $pageId)
    {
        $this->facebook = $facebook;
        $this->pageId = $pageId;
    }

    /**
     * {@inheritdoc}
     */
    public function canPublish(Message $message): bool
    {
        $canPublish = !empty(array_intersect($message->getNetworksToPublishOn(), [SocialNetwork::ANY, SocialNetwork::FACEBOOK]));
        return $canPublish;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Message $message): bool
    {
        if (!$this->canPublish($message)) {
            throw new MessageNotIntendedForPublisher(SocialNetwork::FACEBOOK);
        }

        try {
            $publishPostEndpoint = '/' . $this->pageId. '/feed';
            $response = $this->facebook->post(
                $publishPostEndpoint,
                $this->prepareParams($message)
            );
            $post = $response->getGraphNode();

            return isset($post['id']) ? !empty($post['id']) : false;
        } catch (Throwable $t) {
            throw new FailureWhenPublishingMessage($t);
        }
    }

    /**
     * @param Message $message
     * @return array
     */
    protected function prepareParams(Message $message): array
    {
        $params = [];
        
        $params['message'] = $message->getMessage();

        if (filter_var($message->getLink(), FILTER_VALIDATE_URL) !== false) {
            $params['link'] = $message->getLink();
        }
        if (filter_var($message->getPictureLink(), FILTER_VALIDATE_URL) !== false) {
            $params['picture'] = $message->getPictureLink();
        }
        if (!empty($message->getCaption())) {
            $params['caption'] = $message->getCaption();
        }
        if (!empty($message->getDescription())) {
            $params['description'] = $message->getDescription();
        }

        return $params;
    }
}
