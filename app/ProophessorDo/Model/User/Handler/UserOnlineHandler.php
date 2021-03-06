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

use Prooph\ProophessorDo\Model\User\Command\RegisterUser;
use Prooph\ProophessorDo\Model\User\Command\UserOnlineCommand;
use Prooph\ProophessorDo\Model\User\Exception\UserAlreadyExists;
use Prooph\ProophessorDo\Model\User\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\User\Exception\UserNotUseIm;
use Prooph\ProophessorDo\Model\User\Query\GetAllUsers;
use Prooph\ProophessorDo\Model\User\Service\ChecksUniqueUsersEmailAddress;
use Prooph\ProophessorDo\Model\User\User;
use Prooph\ProophessorDo\Model\User\UserCollection;
use React\Promise\Deferred;

class UserOnlineHandler
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

    public function __invoke(UserOnlineCommand $command): void
    {
        global $server;
        if(!isset($server))
            throw UserNotUseIm::withUserId($command->userId());
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
