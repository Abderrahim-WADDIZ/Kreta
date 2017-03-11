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

namespace Kreta\TaskManager\Infrastructure\Symfony\Command;

use Kreta\SharedKernel\Application\CommandBus;
use Kreta\SharedKernel\Application\QueryBus;
use Kreta\TaskManager\Application\Command\Project\Task\CreateTaskCommand;
use Kreta\TaskManager\Application\Query\Organization\OrganizationOfIdQuery;
use Kreta\TaskManager\Application\Query\Project\FilterProjectsQuery;
use Kreta\TaskManager\Domain\Model\Project\Task\TaskPriority;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskFixturesCommand extends Command
{
    const TASK_PRIORITIES = [TaskPriority::LOW, TaskPriority::MEDIUM, TaskPriority::HIGH];

    private $commandBus;
    private $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        parent::__construct('kreta:task-manager:fixtures:tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $amount = 1000;
        $output->writeln('');
        $output->writeln('Loading tasks...');
        $progress = new ProgressBar($output, $amount);
        $progress->start();
        $i = 0;

        while ($i < $amount) {
            $userId = UserFixturesCommand::USER_IDS[array_rand(UserFixturesCommand::USER_IDS)];

            $this->queryBus->handle(
                new FilterProjectsQuery(
                    $userId,
                    0,
                    1
                ),
                $projects
            );

            foreach ($projects as $project) {
                $this->queryBus->handle(
                    new OrganizationOfIdQuery(
                        $project['organization_id'],
                        $userId
                    ),
                    $organization
                );
                $this->commandBus->handle(
                    new CreateTaskCommand(
                        'Task ' . $i,
                        'The description of the task ' . $i,
                        $userId,
                        $organization['owners'][0]['id'],
                        self::TASK_PRIORITIES[array_rand(self::TASK_PRIORITIES)],
                        $project['id']
                    )
                );
                ++$i;
                $progress->advance();
            }
        }
        $progress->finish();
        $output->writeln('');
    }
}
