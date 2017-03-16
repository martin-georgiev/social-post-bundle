<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

use DomainException;
use Throwable;

class FailureWhenPublishingSocialPost extends DomainException
{
    public function __construct(Throwable $previous)
    {
        $message = sprintf('Cannot publish post. Last known error was: %s', $previous->getMessage());
        parent::__construct($message, 0, $previous);
    }
}
