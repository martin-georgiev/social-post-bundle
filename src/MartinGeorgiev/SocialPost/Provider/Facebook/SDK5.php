<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider\Facebook;

use Facebook\Facebook;
use MartinGeorgiev\SocialPost\Message\Facebook\StatusUpdate;
use MartinGeorgiev\SocialPost\Message\Message;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingSocialPost;
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
     * @param string $pageId Identifier of the page, on which the post will be published
     */
    public function __construct(Facebook $facebook, string $pageId)
    {
        $this->facebook = $facebook;
        $this->pageId = $pageId;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Message $message): bool
    {
        try {
            $publishPostEndpoint = '/' . $this->pageId. '/feed';
            $response = $this->facebook->post(
                $publishPostEndpoint,
                $this->prepareParams($message)
            );
            $post = $response->getGraphNode();

            return !empty($post['id']);
        } catch (Throwable $t) {
            throw new FailureWhenPublishingSocialPost($t);
        }
    }

    /**
     * @param StatusUpdate $statusUpdate
     * @return array
     */
    protected function prepareParams(StatusUpdate $statusUpdate): array
    {
        $params = [];
        
        $params['message'] = $statusUpdate->getMessage();

        if (filter_var($statusUpdate->getLink(), FILTER_VALIDATE_URL) !== false) {
            $params['link'] = $statusUpdate->getLink();
        }
        if (filter_var($statusUpdate->getPictureLink(), FILTER_VALIDATE_URL) !== false) {
            $params['picture'] = $statusUpdate->getPictureLink();
        }
        if (!empty($statusUpdate->getCaption())) {
            $params['caption'] = $statusUpdate->getCaption();
        }
        if (!empty($statusUpdate->getDescription())) {
            $params['description'] = $statusUpdate->getDescription();
        }

        return $params;
    }
}
