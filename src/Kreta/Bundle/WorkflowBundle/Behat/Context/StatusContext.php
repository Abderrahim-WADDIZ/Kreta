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

namespace Kreta\Bundle\WorkflowBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Kreta\Bundle\CoreBundle\Behat\Context\DefaultContext;

/**
 * Class StatusContext.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class StatusContext extends DefaultContext
{
    /**
     * Populates the database with statuses.
     *
     * @param \Behat\Gherkin\Node\TableNode $statuses The statuses
     *
     *
     * @Given /^the following statuses exist:$/
     */
    public function theFollowingStatusesExist(TableNode $statuses)
    {
        foreach ($statuses as $statusData) {
            $workflow = $this->get('kreta_workflow.repository.workflow')
                ->findOneBy(['name' => $statusData['workflow']], false);

            $status = $this->get('kreta_workflow.factory.status')->create($statusData['name'], $workflow);
            $status
                ->setColor($statusData['color'])
                ->setName($statusData['name']);

            $this->setId($status, $statusData['id']);

            $this->get('kreta_workflow.repository.status')->persist($status);
        }
    }
}
