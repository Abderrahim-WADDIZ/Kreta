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

namespace Kreta\Component\Project\Repository;

use Kreta\Component\Core\Repository\EntityRepository;
use Kreta\Component\Organization\Model\Interfaces\OrganizationInterface;
use Kreta\Component\User\Model\Interfaces\UserInterface;

/**
 * Class ProjectRepository.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ProjectRepository extends EntityRepository
{
    /**
     * Finds all the projects where user given is participant.
     * Also supports organization criteria.
     *
     * Can do ordering, limit and offset.
     *
     * @param UserInterface         $user         The user
     * @param OrganizationInterface $organization The organization
     * @param string[]              $sorting      Array which contains the sorting as key/val
     * @param int                   $limit        The limit
     * @param int                   $offset       The offset
     *
     * @return \Kreta\Component\Project\Model\Interfaces\ProjectInterface[]
     */
    public function findByParticipant(
        UserInterface $user,
        OrganizationInterface $organization = null,
        array $sorting = [],
        $limit = null,
        $offset = null
    ) {
        $queryBuilder = $this->getQueryBuilder();
        if ($organization instanceof OrganizationInterface) {
            $queryBuilder = $this->addCriteria($queryBuilder, ['organization' => $organization]);
        }

        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->eq('par.user', ':user'),
            $queryBuilder->expr()->eq('opar.user', ':user')
        ));
        $queryBuilder->setParameter('user', $user);
        $this->orderBy($queryBuilder, $sorting);
        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }
        if ($offset) {
            $queryBuilder->setFirstResult($offset);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueryBuilder()
    {
        return parent::getQueryBuilder()
            ->addSelect(['img', 'i', 'o', 'w', 'c'])
            ->leftJoin('p.image', 'img')
            ->leftJoin('p.issues', 'i')
            ->leftJoin('p.organization', 'o')
            ->leftJoin('o.participants', 'opar')
            ->leftJoin('p.participants', 'par')
            ->join('p.creator', 'c')
            ->join('p.workflow', 'w');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAlias()
    {
        return 'p';
    }
}
