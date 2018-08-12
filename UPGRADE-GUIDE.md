## From v2.0 to v3.0
Some community members expressed interest in using the SocialPost project without its tight Symfony coupling. With version 3 the project splits in two separate repositories. Because of this [`martin-georgiev/social-post-bundle`](https://github.com/martin-georgiev/social-post-bundle/), which now contains only logic specific to the Symfony bundle, has a new dependency, [`martin-georgiev/social-post`](https://github.com/martin-georgiev/social-post/). The latter one contains all the framework-agnostic logic for constructing and sending social network updates. Further to the repository split there are some namespace and class name changes:
- Namespace for `martin-georgiev/social-post-bundle` is `MartinGeorgiev\SocialPostBundle`. This means when requiring the bundle in `AppKernel.php` it has to be with the new FQNS.(`MartinGeorgiev\SocialPostBundle\SocialBundle`)
- Namespace for `martin-georgiev/social-post` is `MartinGeorgiev\SocialPost`. This means when creating a new instance for `Message` it has to be imported from its new FQNS (`MartinGeorgiev\SocialPost\Message`).
- The enumeration class with social network names (previously `MartinGeorgiev\SocialPost\Provider\SocialNetwork`) is renamed to `MartinGeorgiev\SocialPost\Provider\Enum`.
- The publishing interface (previously `MartinGeorgiev\SocialPost\Provider\SocialNetworkPublisher`) is renamed to `MartinGeorgiev\SocialPost\Publisher`.


## From v1.0 to v2.0
- New class `MartinGeorgiev\SocialPost\Provider\Message` to represent any post to a social network.
Its constructor takes the same arguments as `SocialNetworkPublisher::publish()` was doing prior v2.0. This new class enables conditional publishing to a subset of the available networks. The default behaviour is to publish on all available networks. Example:

*This message will be published on LinkedIn and Twitter, but not on Facebook*

    <?php
    //...
    $message = new \MartinGeorgiev\SocialPost\Provider\Message('your test message');
    $message->setNetworksToPublishOn([SocialNetwork::LINKEDIN, SocialNetwork::TWITTER]);
    $container->get('social_post')->publish($message);



- `SocialNetworkPublisher` interface (and publishing) changed. The long list of arguments on `publish()` was replaced with an instance of `MartinGeorgiev\SocialPost\Provider\Message`. See the example below:

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
