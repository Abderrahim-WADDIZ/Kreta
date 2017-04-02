<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Kreta\TaskManager\Infrastructure\Symfony\Framework;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Http\HttplugBundle\HttplugBundle;
use Kreta\TaskManager\Infrastructure\Symfony\Bundle\AppBundle;
use Nelmio\CorsBundle\NelmioCorsBundle;
use OldSound\RabbitMqBundle\OldSoundRabbitMqBundle;
use Overblog\GraphQLBundle\OverblogGraphQLBundle;
use PSS\SymfonyMockerContainer\DependencyInjection\MockerContainer;
use Sensio\Bundle\DistributionBundle\SensioDistributionBundle;
use SimpleBus\SymfonyBridge\DoctrineOrmBridgeBundle;
use SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle;
use SimpleBus\SymfonyBridge\SimpleBusEventBusBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Warezgibzzz\QueryBusBundle\WarezgibzzzQueryBusBundle;

class AppKernel extends Kernel
{
    public function registerBundles() : array
    {
        $bundles = [
            new AppBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
            new DoctrineOrmBridgeBundle(),
            new FrameworkBundle(),
            new HttplugBundle(),
            new MonologBundle(),
            new NelmioCorsBundle(),
            new OldSoundRabbitMqBundle(),
            new OverblogGraphQLBundle(),
            new SecurityBundle(),
            new SimpleBusCommandBusBundle(),
            new SimpleBusEventBusBundle(),
            new SwiftmailerBundle(),
            new TwigBundle(),
            new WarezgibzzzQueryBusBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new DebugBundle();
            $bundles[] = new DoctrineFixturesBundle();
            $bundles[] = new SensioDistributionBundle();
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    public function getRootDir() : string
    {
        return __DIR__;
    }

    public function getCacheDir() : string
    {
        return dirname(__DIR__) . '/../../../../../var/cache/' . $this->getEnvironment();
    }

    public function getLogDir() : string
    {
        return dirname(__DIR__) . '/../../../../../var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader) : void
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    protected function getContainerBaseClass() : string
    {
        if ('test' === $this->environment) {
            return MockerContainer::class;
        }

        return parent::getContainerBaseClass();
    }
}
