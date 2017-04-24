<?php

declare(strict_types=1);

namespace MartinGeorgiev\SocialPost\Provider;

/**
 * Enumeration for supported social netwokrs
 *
 * @since 2.0.0
 * @author Martin Georgiev <martin.georgiev@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/martin-georgiev/social-post-bundle Package's homepage
 */
class SocialNetwork
{
    /**
     * @var string Used to identify any network
     */
    const ANY = 'any';

    /**
     * @var string
     */
    const FACEBOOK = 'facebook';

    /**
     * @var string
     */
    const TWITTER = 'twitter';
}
