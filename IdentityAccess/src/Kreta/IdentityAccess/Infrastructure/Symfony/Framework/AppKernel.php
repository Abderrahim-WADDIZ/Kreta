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

namespace Kreta\IdentityAccess\Infrastructure\Symfony\Framework;

use BenGorFile\DoctrineORMBridgeBundle\BenGorFileDoctrineORMBridgeBundle;
use BenGorFile\FileBundle\BenGorFileBundle;
use BenGorFile\GaufretteFilesystemBridgeBundle\BenGorFileGaufretteFilesystemBridgeBundle;
use BenGorFile\SimpleBusBridgeBundle\BenGorFileSimpleBusBridgeBundle;
use BenGorFile\SimpleBusBridgeBundle\BenGorFileSimpleBusDoctrineORMBridgeBundle;
use BenGorUser\UserBundle\BenGorUserBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Knp\Bundle\GaufretteBundle\KnpGaufretteBundle;
use Kreta\IdentityAccess\Infrastructure\Symfony\DependencyInjection\Compiler\OverrideUserDataTransformerServicePass;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Nelmio\CorsBundle\NelmioCorsBundle;
use OldSound\RabbitMqBundle\OldSoundRabbitMqBundle;
use Sensio\Bundle\DistributionBundle\SensioDistributionBundle;
use SimpleBus\AsynchronousBundle\SimpleBusAsynchronousBundle;
use SimpleBus\RabbitMQBundleBridge\SimpleBusRabbitMQBundleBridgeBundle;
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
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
            new DoctrineOrmBridgeBundle(),
            new FrameworkBundle(),
            new LexikJWTAuthenticationBundle(),
            new MonologBundle(),
            new NelmioCorsBundle(),
            new SecurityBundle(),
            new SimpleBusCommandBusBundle(),
            new SimpleBusEventBusBundle(),
            new SwiftmailerBundle(),
            new TwigBundle(),

            new SimpleBusAsynchronousBundle(),
            new SimpleBusRabbitMQBundleBridgeBundle(),
            new OldSoundRabbitMqBundle(),

            new KnpGaufretteBundle(),
            new BenGorFileGaufretteFilesystemBridgeBundle(),
            new BenGorFileDoctrineORMBridgeBundle(),
            new BenGorFileSimpleBusBridgeBundle(),
            new BenGorFileSimpleBusDoctrineORMBridgeBundle(),
            new BenGorFileBundle(),

            new \BenGorUser\TwigBridgeBundle\TwigBridgeBundle(),
            new \BenGorUser\SymfonyRoutingBridgeBundle\SymfonyRoutingBridgeBundle(),
            new \BenGorUser\SymfonySecurityBridgeBundle\SymfonySecurityBridgeBundle(),
            new \BenGorUser\SwiftMailerBridgeBundle\SwiftMailerBridgeBundle(),
            new \BenGorUser\DoctrineORMBridgeBundle\DoctrineORMBridgeBundle(),
            new \BenGorUser\SimpleBusBridgeBundle\SimpleBusBridgeBundle(),
            new \BenGorUser\SimpleBusBridgeBundle\SimpleBusDoctrineORMBridgeBundle(),
            new BenGorUserBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new DebugBundle();
            $bundles[] = new SensioDistributionBundle();
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__) . '/../../../../../var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__) . '/../../../../../var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    protected function buildContainer()
    {
        $container = parent::buildContainer();
        $container->addCompilerPass(
            new OverrideUserDataTransformerServicePass($this),
            PassConfig::TYPE_OPTIMIZE
        );

        return $container;
    }
}
