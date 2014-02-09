<?php

namespace Saxulum\Tests\BundleProvider\Sample\Controller;

use Saxulum\RouteController\Annotation\Route;

class TestController
{
    /**
     * @Route("/", bind="index")
     */
    public function indexAction()
    {
        return 'index';
    }
}
