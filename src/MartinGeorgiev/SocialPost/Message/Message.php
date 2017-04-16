<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Message;

/**
 * Main contract for a new social post
 *
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
interface Message
{
    /**
     * @return string The message to post
     */
    public function getMessage(): string;
}
