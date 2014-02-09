<?php

namespace Saxulum\Tests\BundleProvider\Provider;

use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Saxulum\Console\Silex\Provider\ConsoleProvider;
use Saxulum\RouteController\Provider\RouteControllerProvider;
use Saxulum\Tests\BundleProvider\Sample\BundleProvider;
use Saxulum\Tests\BundleProvider\Sample\Entity\SampleEntity;
use Saxulum\Translation\Silex\Provider\TranslationProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class BundleProviderTest extends WebTestCase
{
    const ENTITY_NAME = 'name';

    public function testControllers()
    {
        $client = $this->createClient();

        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals('index', $client->getResponse()->getContent());
    }

    public function testCommands()
    {
        $app = $this->createApplication();

        $app->boot();

        $input = new ArrayInput(array(
            'command' => 'sample:command',
            'value' => 'value'
        ));

        $output = new BufferedOutput();

        $app['console']->setAutoExit(false);
        $app['console']->run($input, $output);

        $this->assertEquals('this is a sample command with value: value', $output->fetch());
    }

    public function testDoctrineOrmMappings()
    {
        $app = $this->createApplication();

        /** @var EntityManager $em */
        $em = $app['orm.em'];

        // create schema tool
        $schemaTool = new SchemaTool($em);

        // get metadata
        $metadatas = $em->getMetadataFactory()->getAllMetadata();

        // create schema
        $schemaTool->createSchema($metadatas);

        $entity = new SampleEntity();
        $entity->setName(self::ENTITY_NAME);

        $em->persist($entity);
        $em->flush();

        /** @var SampleEntity $entityFromDB */
        $entityFromDB = $em
            ->getRepository(get_class(new SampleEntity()))
            ->findOneBy(array(), array('id' => 'DESC'))
        ;

        $this->assertEquals(self::ENTITY_NAME, $entityFromDB->getName());

        // remove the schema
        $schemaTool->dropSchema($metadatas);
    }

    public function testTranslatorRessources()
    {
        $app = $this->createApplication();

        $this->assertEquals('messages', $app['translator']->trans('value', array(), 'messages'));
    }

    public function testTwigLoaderFilesystemPath()
    {
        $app = $this->createApplication();

        $this->assertEquals("test\n", $app['twig']->render('@SaxulumTestsBundleProviderSample/test.html.twig'));
    }

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;

        $app->register(new ServiceControllerServiceProvider());
        $app->register(new RouteControllerProvider(), array(
            'route_controller_cache' => __DIR__ . '/../../../../../cache/'
        ));

        $app->register(new ConsoleProvider(), array(
            'console.cache' => __DIR__ . '/../../../../../cache'
        ));

        $app->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_sqlite',
                'path'     => __DIR__ . '/../../../../../cache/app.db',
            ),
        ));

        $app->register(new DoctrineOrmServiceProvider(), array(
            'orm.proxies_dir' => __DIR__ . '/../../../../../doctrine/proxies'
        ));

        $app->register(new TranslationServiceProvider());
        $app->register(new TranslationProvider(), array(
            'translation_cache' => __DIR__ . '/../../../../../cache/'
        ));

        $app->register(new TwigServiceProvider());

        $app->register(new BundleProvider());

        return $app;
    }
}
