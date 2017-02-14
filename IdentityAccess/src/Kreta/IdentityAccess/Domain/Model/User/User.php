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

namespace Kreta\IdentityAccess\Domain\Model\User;

use BenGorUser\User\Domain\Model\User as BaseUser;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;

class User extends BaseUser
{
    private $fullName;
    private $username;

    protected function __construct(UserId $id, UserEmail $email, array $userRoles, UserPassword $password)
    {
        parent::__construct($id, $email, $userRoles, $password);
        $this->username = Username::fromEmail($email);
    }

    public function editProfile(UserEmail $email, Username $username, FullName $fullName)
    {
        $this->email = $email;
        $this->username = $username;
        $this->fullName = $fullName;
        $this->updatedOn = new \DateTimeImmutable();

        $this->publish(
            new UserProfileEdited(
                $this->id(),
                $this->email()
            )
        );
    }

    public function username() : Username
    {
        return $this->username;
    }

    public function fullName() : ? FullName
    {
        return $this->fullName;
    }
}
