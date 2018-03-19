[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/martin-georgiev/social-post-bundle/badges/quality-score.png)](https://scrutinizer-ci.com/g/martin-georgiev/social-post-bundle/)
[![Build Status](https://api.travis-ci.org/martin-georgiev/social-post-bundle.svg?branch=master)](https://www.travis-ci.org/martin-georgiev/social-post-bundle/branches)
----
## Upgrading? Looking for older version?
Read the [upgrade guide](UPGRADE-GUIDE.md) or [check v1](https://github.com/martin-georgiev/social-post-bundle/releases/tag/1.0.0).


----
## What's this?
This is a [Symfony](https://www.symfony.com) bundle written in [PHP 7](https://secure.php.net/manual/en/migration70.new-features.php) that provides an easy way for simultaneous publishing to social networks. Currently, it integrates with Facebook, LinkedIn and Twitter.


----
## How to install it?
Recommended way is through [Composer](https://getcomposer.org/download/)

    composer require "martin-georgiev/social-post-bundle"
    

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
                new \MartinGeorgiev\SocialPost\SocialPostBundle(),
            ];
            return $bundles;
        }
        // ...
    }

*Post a test message*
    
    # Some Symfony container aware class
    <?php
    //...
    $message = new \MartinGeorgiev\SocialPost\Provider\Message('your test message');
    $container->get('social_post')->publish($message);
    
__Important!__ When publishing on Twitter only the values for `$message` and `$link` arguments (of the `Message` instance) will be used. This is due to Twitter's limited features for tweet customisation.

----
## Additional help
Facebook doesn't support non-expiring user access tokens. Instead, you can obtain a permanent page access token. When using such tokens you can act and post as the page itself. More information about the page access tokens from the official [Facebook documentation](https://developers.facebook.com/docs/facebook-login/access-tokens/expiration-and-extension#extendingpagetokens). Some Stackoverflow answers ([here](https://stackoverflow.com/a/21927690/3425372) and [here](https://stackoverflow.com/a/28418469/3425372)) also prove to be helpful. 

----
## License
This package is licensed under the MIT License. Please, respect that!
