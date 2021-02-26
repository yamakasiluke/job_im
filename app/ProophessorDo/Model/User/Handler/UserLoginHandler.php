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

use Prooph\ProophessorDo\Model\User\Command\UserLoginCommand;
use Prooph\ProophessorDo\Model\User\Command\UserOnlineCommand;
use Prooph\ProophessorDo\Model\User\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\User\Query\GetAllUsers;
use Prooph\ProophessorDo\Model\User\UserCollection;
use Prooph\ProophessorDo\Projection\User\UserFinder;
use React\Promise\Deferred;

class UserLoginHandler
{
    /**
     * @var int
     */
    private $fd;
    /**
     * @var UserCollection
     */
    private $userCollection;

    public function __construct(
        UserCollection $userCollection
    ) {
        $this->userCollection = $userCollection;
    }

    public function __invoke(UserLoginCommand $command): void
    {
        if (! $user = $this->userCollection->get($command->userId())) {
            throw UserNotFound::withUserId($command->userId());
        } else {
//            global $server, $frame;
//            $user->userOnline($frame->fd);
            $user->userOnline($command->fd());
        }

        $this->userCollection->save($user);
    }
}
