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

namespace Kreta\TaskManager\Domain\Model\Project;

interface ProjectRepository
{
    public function projectOfId(ProjectId $id) : ?Project;

    public function query($specification) : array;

    public function singleResultQuery($specification) : ?Project;

    public function persist(Project $project) : void;

    public function remove(Project $project) : void;

    public function count($specification) : int;
}
