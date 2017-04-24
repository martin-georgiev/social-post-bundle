<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

use DomainException;
use Throwable;

/**
 * @since 1.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class FailureWhenPublishingMessage extends DomainException
{
    public function __construct(Throwable $previous)
    {
        $message = sprintf('Cannot publish message. Last known error was: %s', $previous->getMessage());
        parent::__construct($message, 0, $previous);
    }
}
