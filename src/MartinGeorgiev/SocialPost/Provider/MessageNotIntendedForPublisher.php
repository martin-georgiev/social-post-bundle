<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

use DomainException;

/**
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class MessageNotIntendedForPublisher extends DomainException
{
    public function __construct(string $socialNetwork)
    {
        $message = sprintf('The message is not intended to be published on %s', $socialNetwork);
        parent::__construct($message);
    }
}
