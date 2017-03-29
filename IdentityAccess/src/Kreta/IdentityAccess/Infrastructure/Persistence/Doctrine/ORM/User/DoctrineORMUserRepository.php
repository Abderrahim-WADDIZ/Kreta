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

namespace Kreta\IdentityAccess\Infrastructure\Persistence\Doctrine\ORM\User;

use BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\DoctrineORMUserRepository as BaseDoctrineORMUserRepository;
use Kreta\IdentityAccess\Domain\Model\User\User;
use Kreta\IdentityAccess\Domain\Model\User\Username;
use Kreta\IdentityAccess\Domain\Model\User\UserRepository;

class DoctrineORMUserRepository extends BaseDoctrineORMUserRepository implements UserRepository
{
    public function userOfUsername(Username $username) : ?User
    {
        return $this->findOneBy(['username.username' => $username->username()]);
    }

    public function usersOfIds(array $userIds) : array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        return $queryBuilder
            ->select('u, field(u.id, :ids) as HIDDEN field')
            ->where($queryBuilder->expr()->in('u.id', ':ids'))
            ->setParameter('ids', $userIds)
            ->orderBy('field')
            ->getQuery()
            ->getResult();
    }

    public function usersOfSearchString($search, array $excludedIds = []) : array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        return $queryBuilder
            ->select('u')
            ->where($queryBuilder->expr()->like('u.username.username', ':search'))
            ->andWhere($queryBuilder->expr()->notIn('u.id', ':ids'))
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('ids', $excludedIds)
            ->getQuery()
            ->getResult();
    }
}
