<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace spec\Kreta\Component\Core\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Prophecy\Argument;
use Kreta\Component\Core\spec\Kreta\Component\Core\Repository\BaseEntityRepository;

/**
 * Class EntityRepositorySpec.
 *
 * @package spec\Kreta\Component\Core\Repository
 */
class EntityRepositorySpec extends BaseEntityRepository
{
    function let(EntityManager $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Component\Core\Repository\EntityRepository');
    }

    function it_extends_doctrines_entity_repository()
    {
        $this->shouldHaveType('Doctrine\ORM\EntityRepository');
    }

    function it_persists_without_flush(EntityManager $manager)
    {
        $manager->persist(Argument::type('Object'))->shouldBeCalled();

        $this->persist(Argument::type('Object'), false);
    }

    function it_persists_with_flush(EntityManager $manager)
    {
        $manager->persist(Argument::type('Object'))->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->persist(Argument::type('Object'), true);
    }

    function it_removes_without_flush(EntityManager $manager)
    {
        $manager->remove(Argument::type('Object'))->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->remove(Argument::type('Object'), true);
    }

    function it_removes_with_flush(EntityManager $manager)
    {
        $manager->remove(Argument::type('Object'))->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->remove(Argument::type('Object'), true);
    }

    function it_finds_nullable(EntityManager $manager)
    {
        $object = Argument::type('Object');

        $manager->find(null, 'id', 0, null)->shouldBeCalled()->willReturn($object);

        $this->find('id')->shouldReturn($object);
    }

    function it_finds_nullable_without_result(EntityManager $manager)
    {
        $manager->find(null, 'id', 0, null)->shouldBeCalled()->willReturn(null);

        $this->find('id')->shouldReturn(null);
    }

    function it_finds(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['id' => 'id'], $comparison);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleResult()->shouldBeCalled()->willReturn($object);

        $this->find('id', false)->shouldReturn($object);
    }

    function it_finds_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['id' => 'id'], $comparison);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleResult()->shouldBeCalled()->willReturn(null);

        $this->find('id', false)->shouldReturn(null);
    }

    function it_finds_all(EntityManager $manager, QueryBuilder $queryBuilder, AbstractQuery $query)
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn([]);

        $this->findAll()->shouldBeArray();
    }

    function it_finds_one_by_eq_nullable(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['name' => 'dummy name'])->shouldReturn($object);
    }

    function it_finds_one_by_eq_nullable_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['name' => 'dummy name'])->shouldReturn(null);
    }

    function it_finds_one_by_eq(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['name' => 'dummy name'], false)->shouldReturn($object);
    }

    function it_finds_one_by_eq_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);

        $query->getSingleResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['name' => 'dummy name'], false)->shouldReturn(null);
    }

    function it_finds_one_by_isNull_nullable(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => null], $comparison, 'isNull');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['name' => null])->shouldReturn($object);
    }

    function it_finds_one_by_isNull_nullable_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => null], $comparison, 'isNull');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['name' => null])->shouldReturn(null);
    }

    function it_finds_one_by_isNull(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => null], $comparison, 'isNull');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['name' => null], false)->shouldReturn($object);
    }

    function it_finds_one_by_isNull_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => null], $comparison, 'isNull');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);

        $query->getSingleResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['name' => null], false)->shouldReturn(null);
    }

    function it_finds_one_by_in_nullable(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['names' => ['dummy name', 'dummy name 2']], $comparison, 'in');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['names' => ['dummy name', 'dummy name 2']])->shouldReturn($object);
    }

    function it_finds_one_by_in_nullable_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['names' => ['dummy name', 'dummy name 2']], $comparison, 'in');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['names' => ['dummy name', 'dummy name 2']])->shouldReturn(null);
    }

    function it_finds_one_by_in(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['names' => ['dummy name', 'dummy name 2']], $comparison, 'in');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['names' => ['dummy name', 'dummy name 2']], false)->shouldReturn($object);
    }

    function it_finds_one_by_in_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['names' => ['dummy name', 'dummy name 2']], $comparison, 'in');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);

        $query->getSingleResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['names' => ['dummy name', 'dummy name 2']], false)->shouldReturn(null);
    }

    function it_finds_one_by_like_nullable(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison, 'like');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['name' => 'dummy name'], true, false)->shouldReturn($object);
    }

    function it_finds_one_by_like_nullable_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison, 'like');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);

        $query->getOneOrNullResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['name' => 'dummy name'], true, false)->shouldReturn(null);
    }

    function it_finds_one_by_like(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison, 'like');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleResult()->shouldBeCalled()->willReturn($object);

        $this->findOneBy(['name' => 'dummy name'], false, false)->shouldReturn($object);
    }

    function it_finds_one_by_like_without_result(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison, 'like');
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);

        $query->getSingleResult()->shouldBeCalled()->willReturn(null);

        $this->findOneBy(['name' => 'dummy name'], false, false)->shouldReturn(null);
    }

    function it_finds_by_eq(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison);
        $this->orderBySpec($queryBuilder, ['name' => 'DESC']);
        $queryBuilder->setMaxResults(10)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setFirstResult(3)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn([$object]);

        $this->findBy(['name' => 'dummy name'], ['name'], 10, 3)->shouldReturn([$object]);
    }

    function it_finds_by_in(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['names' => ['dummy name', 'dummy name 2']], $comparison, 'in');
        $this->orderBySpec($queryBuilder, ['name' => 'ASC']);
        $queryBuilder->setMaxResults(10)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setFirstResult(3)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn([$object]);

        $this->findBy(['names' => ['dummy name', 'dummy name 2']], ['name' => 'ASC'], 10, 3)->shouldReturn([$object]);
    }

    function it_finds_by_isNull(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => null], $comparison, 'isNull');
        $this->orderBySpec($queryBuilder, ['name' => 'DESC']);
        $queryBuilder->setMaxResults(10)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setFirstResult(3)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn([$object]);

        $this->findBy(['name' => null], ['name'], 10, 3)->shouldReturn([$object]);
    }

    function it_finds_by_like(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Comparison $comparison,
        AbstractQuery $query
    )
    {
        $object = Argument::type('Object');

        $queryBuilder = $this->getQueryBuilderSpec($manager, $queryBuilder);
        $this->addCriteriaSpec($queryBuilder, $expr, ['name' => 'dummy name'], $comparison, 'like');
        $this->orderBySpec($queryBuilder, ['name' => 'DESC']);
        $queryBuilder->setMaxResults(10)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setFirstResult(3)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn([$object]);

        $this->findBy(['name' => 'dummy name'], ['name'], 10, 3, false)->shouldReturn([$object]);
    }
}