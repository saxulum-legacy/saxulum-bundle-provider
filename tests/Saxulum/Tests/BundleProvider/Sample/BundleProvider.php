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
        $this->addTranslatorResources($app);
        $this->addTwigLoaderFilesystemPath($app);
    }

    public function boot(Application $app) {}
}
