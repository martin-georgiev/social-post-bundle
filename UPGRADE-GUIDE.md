## From v1.0 to v2.0
- New class `\MartinGeorgiev\SocialPost\Provider\Message` to represent any post to a social network.
Its constructor takes the same arguments as `SocialNetworkPublisher::publish()` was doing prior v2.0. This new class enables conditional publishing to a subset of the available networks. The default behaviour is to publish on all available networks. Example:

*This message will be published on LinkedIn and Twitter, but not on Facebook*

    <?php
    //...
    $message = new \MartinGeorgiev\SocialPost\Provider\Message('your test message');
    $message->setNetworks([SocialNetwork::LINKEDIN, SocialNetwork::TWITTER]);
    $container->get('social_post')->publish($message);



- `SocialNetworkPublisher` interface (and publishing) changed. The long list of arguments on `publish()` was replaced with an instance of `\MartinGeorgiev\SocialPost\Provider\Message`. See the example below:

*Prior v2.0*
    
    <?php
    //...
    $container->get('social_post')->publish('your test message');


*Since v2.0*
    
    <?php
    //...
    $message = new \MartinGeorgiev\SocialPost\Provider\Message('your test message');
    $container->get('social_post')->publish($message);



- A new `FailureWhenPublishingMessage` exception replaced the now dropped `FailureWhenPublishingSocialPost`. The new exception is still thrown for the same situations as the old one.