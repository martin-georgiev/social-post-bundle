[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/martin-georgiev/social-post-bundle/badges/quality-score.png)](https://scrutinizer-ci.com/g/martin-georgiev/social-post-bundle/?branch=master)
[![Build Status](https://api.travis-ci.org/martin-georgiev/social-post-bundle.svg?branch=master)](https://www.travis-ci.org/martin-georgiev/social-post-bundle)
[![Coverage Status](https://coveralls.io/repos/github/martin-georgiev/social-post-bundle/badge.svg?branch=master)](https://coveralls.io/github/martin-georgiev/social-post-bundle?branch=master)
[![Latest Stable Version](https://poser.pugx.org/martin-georgiev/social-post-bundle/version)](https://packagist.org/packages/martin-georgiev/social-post-bundle)
[![Total Downloads](https://poser.pugx.org/martin-georgiev/social-post-bundle/downloads)](https://packagist.org/packages/martin-georgiev/social-post-bundle)
----
## Upgrading?
Check the [upgrade guide](UPGRADE-GUIDE.md).


----
## What's this?
This is a [Symfony](https://www.symfony.com) bundle written in [PHP 7.1](https://secure.php.net/manual/en/migration71.new-features.php) that wraps [martin-georgiev/social-post](https://github.com/martin-georgiev/social-post) - an easy way for simultaneous publishing to multiple social networks. Currently, it integrates with Facebook, LinkedIn and Twitter.


----
## How to install it?
Recommended way is through [Composer](https://getcomposer.org/download/)

    composer require martin-georgiev/social-post-bundle
    

----
## Integration with Symfony
*Add social networks configuration*

    # Usually part of config.yml
    social_post:
        publish_on: [facebook, linkedin, twitter]         # List which Social networks you will be publishing to and configure your access to them as shown below
        providers:
            facebook:
                app_id: "YOUR-FACEBOOK-APP-ID"
                app_secret: "YOUR-FACEBOOK-APP-SECRET"
                default_access_token: "YOUR-FACEBOOK-NON-EXPIRING-PAGE-ACCESS-TOKEN"
                page_id: "YOUR-FACEBOOK-PAGE-ID"
                enable_beta_mode: true
                default_graph_version: "v2.8"             # Optional, also supports "mcrypt" and "urandom". Default uses the latest graph version.
                persistent_data_handler: "memory"         # Optional, also supports "session". Default is "memory".
                pseudo_random_string_generator: "openssl" # Optional, also supports "mcrypt" and "urandom". Default is "openssl".
                http_client_handler: "curl"               # Optional, also supports "stream" and "guzzle". Default is "curl".
            linkedin:
                client_id: "YOUR-LINKEDIN-APP-CLIENT-ID"
                client_secret: "YOUR-LINKEDIN-APP-CLIENT-SECRET"
                access_token: "YOUR-LINKEDIN-60-DAYS-LONG-USER-ACCESS-TOKEN"
                company_page_id: "YOUR-LINKEDIN-COMPANY-PAGE-ID"
            twitter:
                consumer_key: "YOUR-TWITTER-APP-CONSUMER-KEY"
                consumer_secret: "YOUR-TWITTER-APP-CONSUMER-SECRET"
                access_token: "YOUR-TWITTER-ACCESS-TOKEN"
                access_token_secret: "YOUR-TWITTER-ACCESS-TOKEN-SECRET"

*Register the bundle*

    # Usually your app/AppKernel.php
    <?php
    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                // ...
                new \MartinGeorgiev\SocialPostBundle\SocialPostBundle(),
            ];
            return $bundles;
        }
        // ...
    }

*Post a test message*
    
    # Some Symfony container aware class
    <?php
    //...
    $message = new \MartinGeorgiev\SocialPost\Message('your test message');
    $container->get('social_post')->publish($message);
    
----
## Additional help
Twitter has limited features for tweet customisation. This means that for tweets only `message` and `link` values (`MartinGeorgiev\SocialPost\Message` instance) will be used.

Facebook doesn't support non-expiring user access tokens. Instead, you can obtain a permanent page access token. When using such tokens you can act and post as the page itself. More information about the page access tokens from the official [Facebook documentation](https://developers.facebook.com/docs/facebook-login/access-tokens/expiration-and-extension#extendingpagetokens). Some Stackoverflow answers ([here](https://stackoverflow.com/a/21927690/3425372) and [here](https://stackoverflow.com/a/28418469/3425372)) also can of help. 

----
## License
This package is licensed under the MIT License.
