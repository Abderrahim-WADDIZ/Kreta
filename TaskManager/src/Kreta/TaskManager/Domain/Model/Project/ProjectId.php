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

use Kreta\SharedKernel\Domain\Model\Identity\Id;

class ProjectId extends Id
{
    public static function generate(?string $id = null) : ProjectId
    {
        return new static($id);
    }
}
