<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider\Facebook;

use Facebook\Facebook;
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
	public function publish(
        string $message,
        string $link = '',
        string $pictureLink = '',
        string $caption = '',
        string $description = ''
    ): bool {
        try {
            $publishPostEndpoint = '/' . $this->pageId. '/feed';
            $response = $this->facebook->post($publishPostEndpoint, $this->prepareParams($message, $link, $pictureLink, $caption, $description));
            $post = $response->getGraphNode();

            return !empty($post['id']);
            
        } catch (Throwable $t) {
            throw new FailureWhenPublishingSocialPost($t);
        }
    }

    /**
     * @param string $message
     * @param string $link
     * @param string $pictureLink
     * @param string $caption
     * @param string $description
     * @return array
     */
    protected function prepareParams(
        string $message,
        string $link,
        string $pictureLink,
        string $caption,
        string $description
    ): array {
        $params = [];
        
        $params['message'] = $message;
        if (filter_var($link, FILTER_VALIDATE_URL) !== false) {
            $params['link'] = $link;
        }
        if (filter_var($pictureLink, FILTER_VALIDATE_URL) !== false) {
            $params['picture'] = $pictureLink;
        }
        if (!empty($caption)) {
            $params['caption'] = $caption;
        }
        if (!empty($description)) {
            $params['description'] = $description;
        }

        return $params;
    }
}
