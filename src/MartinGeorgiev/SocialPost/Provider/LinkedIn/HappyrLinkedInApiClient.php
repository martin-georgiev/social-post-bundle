<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider\LinkedIn;

use Happyr\LinkedIn\LinkedIn;
use MartinGeorgiev\SocialPost\Provider\FailureWhenPublishingMessage;
use MartinGeorgiev\SocialPost\Provider\Message;
use MartinGeorgiev\SocialPost\Provider\MessageNotIntendedForPublisher;
use MartinGeorgiev\SocialPost\Provider\SocialNetwork;
use MartinGeorgiev\SocialPost\Provider\SocialNetworkPublisher;
use Throwable;

/**
 * Provider for publishing on a LinkedIn page.
 * @see https://developer.linkedin.com/docs/company-pages
 * @see https://github.com/Happyr/LinkedIn-API-client
 *
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class HappyrLinkedInApiClient implements SocialNetworkPublisher
{
    /**
     * @var LinkedIn
     */
    private $linkedIn;

    /**
     * @var string
     */
    private $companyPageId;

    /**
     * @param LinkedIn $linkedIn Ready to use instance of the Happyr's LinkedIn API client
     * @param string $accessToken Access token for a user with administrative rights of the page
     * @param string $companyPageId Identifier of the company page, on which the share will be published
     */
    public function __construct(LinkedIn $linkedIn, string $accessToken, string $companyPageId)
    {
        $linkedIn->setAccessToken($accessToken);
        $this->linkedIn = $linkedIn;
        $this->companyPageId = $companyPageId;
    }

    /**
     * {@inheritdoc}
     */
    public function canPublish(Message $message): bool
    {
        $canPublish = !empty(array_intersect($message->getNetworksToPublishOn(), [SocialNetwork::ANY, SocialNetwork::LINKEDIN]));
        return $canPublish;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Message $message): bool
    {
        if (!$this->canPublish($message)) {
            throw new MessageNotIntendedForPublisher(SocialNetwork::LINKEDIN);
        }

        try {
            $publishShareEndpoint = 'v1/companies/' . $this->companyPageId. '/shares';
            $options = ['json' => $this->prepareShare($message)];
            $share = $this->linkedIn->post($publishShareEndpoint, $options);

            return isset($share['updateKey']) ? !empty($share['updateKey']) : false;
        } catch (Throwable $t) {
            throw new FailureWhenPublishingMessage($t);
        }
    }

    /**
     * @param Message $message
     * @return array
     */
    protected function prepareShare(Message $message): array
    {
        $share = [];

        $share['comment'] = $message->getMessage();
        $share['visibility']['code'] = 'anyone';

        if (filter_var($message->getLink(), FILTER_VALIDATE_URL) !== false) {
            $share['content']['submitted-url'] = $message->getLink();
        }
        if (filter_var($message->getPictureLink(), FILTER_VALIDATE_URL) !== false) {
            $share['content']['submitted-image-url'] = $message->getPictureLink();
        }
        if (!empty($message->getCaption())) {
            $share['content']['title'] = $message->getCaption();
        }
        if (!empty($message->getDescription())) {
            $share['content']['description'] = $message->getDescription();
        }

        return $share;
    }
}
