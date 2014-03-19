saxulum-bundle-provider
=======================

**works with plain silex-php**

[![Build Status](https://api.travis-ci.org/saxulum/saxulum-bundle-provider.png?branch=master)](https://travis-ci.org/saxulum/saxulum-bundle-provider)
[![Total Downloads](https://poser.pugx.org/saxulum/saxulum-bundle-provider/downloads.png)](https://packagist.org/packages/saxulum/saxulum-bundle-provider)
[![Latest Stable Version](https://poser.pugx.org/saxulum/saxulum-bundle-provider/v/stable.png)](https://packagist.org/packages/saxulum/saxulum-bundle-provider)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/saxulum/saxulum-bundle-provider/badges/quality-score.png?s=6e3b60fbe45e652fe01b71d77f7564e07b4fc5ed)](https://scrutinizer-ci.com/g/saxulum/saxulum-bundle-provider/)

Features
--------

* Register commands, controllers, doctrine orm entities, translations, twig templates

Requirements
------------

* php >=5.3
* Silex >=1.1

Suggestions
-----------
* [Doctrine ORM Service Provider][1] >= 1.0.4
* [Saxulum Console][2] >= 1.2.0
* [Saxulum Route Controller Provider][3] >= 1.0.3
* [Saxulum Translation Provider][4] >= 1.0.0

Installation
------------

Through [Composer](http://getcomposer.org) as [saxulum/saxulum-bundle-provider][5].

### Console

Use the installation guide of the [Saxulum Console][2].

### Controller

Use the installation guide of the [Saxulum Route Controller Provider][3].

### Doctrine ORM

#### AnnotationRegistry

Add this line after you added the `autoload.php` from composer

```{.php}
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
```

Use the installation guide of the [Doctrine DBAL Service Provider][6].
Use the installation guide of the [Doctrine ORM Service Provider][1] without
the mapping settings.

#### Example

``` {.php}
$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => __DIR__ . '/../../../../../doctrine/proxies'
));
```

### Translation

Use the installation guide of the [Saxulum Translation Provider][4].

### Twig

Use the installation guide of the [Twig Provider][7].

### Bundle

Create a provider which extends `Saxulum\BundleProvider\Provider\AbstractBundleProvider`
and register it.

#### Example Provider

``` {.php}
<?php

namespace Saxulum\Tests\BundleProvider\Sample;

use Saxulum\BundleProvider\Provider\AbstractBundleProvider;
use Silex\Application;

class BundleProvider extends AbstractBundleProvider
{
    public function register(Application $app)
    {
        $this->addCommands($app);
        $this->addControllers($app);
        $this->addDoctrineOrmMappings($app);
        $this->addTranslatorRessources($app);
        $this->addTwigLoaderFilesystemPath($app);
    }

    public function boot(Application $app) {}
}
```

``` {.php}
$app->register(new BundleProvider());
```

Usage
-----

### Console

Add commands to the `Command` folder relative to your `BundleProvider`
extending the `Saxulum\Console\Command\AbstractCommand`.

### Controller

Add controllers to the `Controller` folder relative to your `BundleProvider`.

### Doctrine ORM

Add entities to the `Entity` folder relative to your `BundleProvider`.

### Translation

Add translations to the `Resources/translations` folder relative to your `BundleProvider`.
For example a file called `messages.en.yml`

### Twig

Add templates to the `Resources/views` folder relative to your `BundleProvider`.
For example a file called `test.html.twig`. You can render it with

``` {.php}
$app['twig']->render('@SaxulumTestsBundleProviderSample/test.html.twig')
```

[1]: https://github.com/dflydev/dflydev-doctrine-orm-service-provider
[2]: https://github.com/saxulum/saxulum-console
[3]: https://github.com/saxulum/saxulum-route-controller-provider
[4]: https://github.com/saxulum/saxulum-translation-provider
[5]: https://github.com/saxulum/saxulum-bundle-provider
[6]: http://silex.sensiolabs.org/doc/providers/doctrine.html
[7]: http://silex.sensiolabs.org/doc/providers/twig.html