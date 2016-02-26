# Remote Media Bundle for Kunstmaan
 
This bundle enables you to store your assets in S3 bucket instead of local filesystem
 
## Installation

`composer require arsthanea/remote-media-bundle`

## Configuration

Add this bundle to your kernel after the `KunstmaanMediaBundle`:

```
…
  $bundles = [
    //
      new Kunstmaan\MediaBundle\KunstmaanMediaBundle(),
      new ArsThanea\RemoteMediaBundle\RemoteMediaBundle(),
    //
  ];
…
```

Setup S3 details in `config.yml`: 

```yml
remote_media:
  cdn:
    # The public URL to your bucket. You may use a CDN for instance
    # If not sure leave null
    #
    media_url: https://a-cdn-for-my-bucket.cloudflare.net
    
    # cached thumbnails can be stored in a prefixed path.
    # the default value is the env name (i.e. "dev/") or no prefix on production
    #
    # cache_prefix: dev
    
    # To speed up cache resolution, provide a doctrine_cache provider:
    #
    # cache_provider: liip_imagine

    s3:
      bucket: acme-bundle
      
      # this is optional and defaults to eu-west-1
      #
      # region: eu-west-1
      
      # please provide AWS access key and secret for this bucket:
      #
      access_key: %aws_access_key%
      access_secret: %aws_access_secret%
```

You need to tweak the `liip_imagine` configuration a bit. Locate the default settings in `config.yml` or other place
and set the `cache` and `data_provider` keys to `remote_media` value:

```yaml
liip_imagine:
  cache: remote_media
  data_loader: remote_media
```

### Setup cache provider

Use [`DoctrineCacheBundle`](http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/caching.html) (already used by Kunstmaan Bundles) to create a cache provider:

```yml
doctrine_cache:
  providers:
    liip_imagine:
      type: redis
```

## Usage

Use `media_url` twig function to replace the media path with a CDN host. If you don’t need the CDN please keep in mind 
that `Media::getUrl()` now returns a full URL instead of just a relative path.

```twig
  <img src="{{ media_url(resource.image.url) }}">
```
