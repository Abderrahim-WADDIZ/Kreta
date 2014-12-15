<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\Api\ApiCoreBundle\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

/**
 * Class StatusContext.
 *
 * @package Kreta\Bundle\Api\ApiCoreBundle\Behat
 */
class StatusContext extends RawMinkContext implements Context, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @Given /^the following statuses exist:$/
     */
    public function theFollowingStatusesExist(TableNode $statuses)
    {
        $manager = $this->kernel->getContainer()->get('doctrine')->getManager();

        foreach ($statuses as $statusData) {
            $project = $this->getKernel()->getContainer()->get('kreta_core.repository.project')
                ->findOneBy(['name' => $statusData['project']]);

            $status = $this->kernel->getContainer()->get('kreta_core.factory.status')->create($statusData['name']);
            $status->setId($statusData['id']);
            $status->setColor($statusData['color']);
            $status->setName($statusData['name']);
            $status->setProject($project);

            $metadata = $manager->getClassMetaData(get_class($status));
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

            $manager->persist($status);
        }

        $manager->flush();
    }
}
