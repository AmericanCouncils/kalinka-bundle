# Kalinka-Bundle

Symfony2 bundle for [Kalinka](https://github.com/AmericanCouncils/kalinka).

## Installation ##

1. require `"ac/kalinka-bundle": "~0.1.0" in your `composer.json`
2. run `composer update ac/kalinka`
3. Instantiate `AC\KalinkaBundle\ACKalinkaBundle` in your `AppKernel`
4. Configure the bundle, described below:

## Configuration ##

```yaml
ac_kalinka:
    #default_authorizer: 'default'
    authorizers:
        default:
            authenticated_role: 'authenticated'     #optional
            anonymous_role: 'anonymous'             #optional
            roles:                                  #map roles to actions and guard policies
                authenticated:
                    document:
                        read: 'allow'
                        index: 'allow'
                anonymous:
                    document:
                        read: 'allow'
                teacher:
                    system:
                        foo: 'allow'
                    document:
                        index: 'allow'
                        create: 'allow'
                        read: 'allow'
                        update: ['owner', 'unlocked']
                        delete: ['owner', 'unlocked']
                admin:
                    system:
                        foo: 'allow'
                        bar: 'allow'
                        baz: 'allow'
                    document:
                        index: 'allow'
                        create: 'allow'
                        read: 'allow'
                        update: 'allow'
                        delete: 'allow'
                student:
                    document:
                        index: 'allow'
                        read: 'allow'
```

## Services ##

The bundle registers the `kalinka.authorizer` service, which you can use in your app:

```php
$document = //...get some document instance, however you do that

$authorizer = $this->container->get('kalinka.authorizer');

if (!$authorizer->can('edit', 'document', $someDocument)) {
    throw new Exception('Computer says no. :(');
}
```

To register guards just use the `kalinka.guard` tag, and specify the domain of the guard.  For example:

```yaml
services:
    app.guard.document:
        class: ACME\AppBundle\Authorization\DocumentGuard
        tags:
            - { name: 'kalinka.guard', tag: 'document' }
```

You can also configure multiple authorizers if you need them.

```yaml
#TODO: document said feature
```
