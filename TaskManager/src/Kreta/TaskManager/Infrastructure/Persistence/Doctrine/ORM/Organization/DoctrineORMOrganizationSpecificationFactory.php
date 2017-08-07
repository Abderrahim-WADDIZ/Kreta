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

namespace Kreta\TaskManager\Infrastructure\Persistence\Doctrine\ORM\Organization;

use Kreta\SharedKernel\Domain\Model\Identity\Slug;
use Kreta\TaskManager\Domain\Model\Organization\OrganizationSpecificationFactory;
use Kreta\TaskManager\Domain\Model\User\UserId;

class DoctrineORMOrganizationSpecificationFactory implements OrganizationSpecificationFactory
{
    public function buildFilterableSpecification(?string $name, UserId $userId, int $offset = 0, int $limit = -1)
    {
        return new DoctrineORMFilterableSpecification($name, $userId, $offset, $limit);
    }

    public function buildBySlugSpecification(Slug $slug)
    {
        return new DoctrineORMBySlugSpecification($slug);
    }
}
