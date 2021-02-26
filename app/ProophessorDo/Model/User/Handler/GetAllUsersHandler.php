<?php
/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2017 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2017 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\ProophessorDo\Model\User\Handler;

use Prooph\ProophessorDo\Model\User\Query\GetAllUsers;
use Prooph\ProophessorDo\Projection\User\UserFinder;
use React\Promise\Deferred;

class GetAllUsersHandler
{
    private $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function __invoke(GetAllUsers $query, Deferred $deferred = null)
    {
        $user = $this->userFinder->findAll();
        if (null === $deferred) {
            return $user;
        }

        $deferred->resolve($user);
    }
}
