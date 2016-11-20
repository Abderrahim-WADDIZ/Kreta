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

namespace Kreta\TaskManager\Application\Query\Project;

class FilterProjectsQuery
{
    private $userId;
    private $organizationId;
    private $offset;
    private $limit;
    private $name;

    public function __construct(
        string $userId,
        int $offset,
        int $limit,
        string $organizationId = null,
        string $name = null
    ) {
        $this->name = $name;
        $this->organizationId = $organizationId;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->userId = $userId;
    }

    public function userId() : string
    {
        return $this->userId;
    }

    public function offset() : int
    {
        return $this->offset;
    }

    public function limit() : int
    {
        return $this->limit;
    }

    public function organizationId()
    {
        return $this->organizationId;
    }

    public function name()
    {
        return $this->name;
    }
}
