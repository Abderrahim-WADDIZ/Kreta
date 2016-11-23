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

class OwnerOfIdQuery
{
    private $organizationId;
    private $userId;

    public function __construct(string $organizationId, string $userId)
    {
        $this->organizationId = $organizationId;
        $this->userId = $userId;
    }

    public function organizationId() : string
    {
        return $this->organizationId;
    }

    public function userId() : string
    {
        return $this->userId;
    }
}
