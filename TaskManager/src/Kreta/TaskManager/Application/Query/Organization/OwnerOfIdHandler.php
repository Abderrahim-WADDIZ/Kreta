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

namespace Kreta\TaskManager\Application\Query\Organization;

use Kreta\TaskManager\Application\DataTransformer\Organization\MemberDataTransformer;
use Kreta\TaskManager\Domain\Model\Organization\Organization;
use Kreta\TaskManager\Domain\Model\Organization\OrganizationDoesNotExistException;
use Kreta\TaskManager\Domain\Model\Organization\OrganizationId;
use Kreta\TaskManager\Domain\Model\Organization\OrganizationRepository;
use Kreta\TaskManager\Domain\Model\Organization\OwnerDoesNotExistException;
use Kreta\TaskManager\Domain\Model\Organization\UnauthorizedOrganizationActionException;
use Kreta\TaskManager\Domain\Model\User\UserId;

class OwnerOfIdHandler
{
    private $repository;
    private $dataTransformer;

    public function __construct(OrganizationRepository $repository, MemberDataTransformer $dataTransformer)
    {
        $this->repository = $repository;
        $this->dataTransformer = $dataTransformer;
    }

    public function __invoke(OwnerOfIdQuery $query)
    {
        $organization = $this->repository->organizationOfId(
            OrganizationId::generate(
                $query->organizationId()
            )
        );
        if (!$organization instanceof Organization) {
            throw new OrganizationDoesNotExistException();
        }
        if (!$organization->isOrganizationMember(UserId::generate($query->userId()))) {
            throw new UnauthorizedOrganizationActionException();
        }

        $ownerId = UserId::generate($query->ownerId());
        if (!$organization->isOwner($ownerId)) {
            throw new OwnerDoesNotExistException($ownerId);
        }

        $this->dataTransformer->write($organization->owner($ownerId));

        return $this->dataTransformer->read();
    }
}
