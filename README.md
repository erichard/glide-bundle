Glide Symfony Bundle
====================

Integrate the great [Glide](http://glide.thephpleague.com/) library in your Symfony app.

Installation
------------

```
$ composer require erichard/glide-bundle
```

Then you need to add the bundle to your AppKernel.

```
$bundles = array(
    ...
    new Erichard\GlideBundle\ErichardGlideBundle(),
    ...
);
```

Enable the routing in your `app/config/routing.yml`

```
_erichard_glide:
    resource: "@ErichardGlideBundle/Resources/config/routing.yml"
```

Configuration
-------------

```
erichard_glide:
    sign_key: ~
    presets:
        product_showcase: # glide options
            w: 540
            h: 540
    servers:
        image:
            source: oneup_flysystem.image_filesystem # A flystem service
            cache: oneup_flysystem.cache_filesystem # A flystem service
            max_image_size: 4000000 # OPTIONAL - number of pixels
            defaults: # OPTIONAL - glide defaults options
                q: 90
                fm: jpg

```

I recommend to use the [oneup/flysystem-bundle](https://github.com/1up-lab/OneupFlysystemBundle) package to manage your flysystems services.

Servers
-------

You can configure as many servers you want. Each server must have its proper `source` and `cache` flysystem adapter. For example you can have a aws server with both source and cache in Amazon S3 buckets and a local server serving locally stored images.

Presets
-------

I prefer to use presets in my projects to avoid having image settings scattered across templates. Check the Glide documentation to [learn more about presets](http://glide.thephpleague.com/1.0/config/defaults-and-presets/).

Security
--------

For enhanced security you should sign each HTTP request by setting the `sign_key` option above.

In addition signing URLs, you can also limit how large images can be generated for each server with the `max_image_size` setting.

Take a look at the Glide documentation to [learn more about security](http://glide.thephpleague.com/1.0/config/security/).

Twig Extension
--------------

A twig extension is provided to generate images URL in your templates. The extension will handle the request signature if you have enabled the feature.

```
{{ glideUrl('image', image.path, {'p': 'product_showcase'}) }}
```

Get glide servers from container
--------------------------------

Servers are accessible publicly in the container. You can grab them with their id `erichard_glide.<name>_server`.

With the exemple from above you can get the server like this.

```
$server = $this->get('erichard_glide.image_server');
```
