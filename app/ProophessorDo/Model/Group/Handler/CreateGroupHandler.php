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

namespace Prooph\ProophessorDo\Model\Group\Handler;

use Prooph\ProophessorDo\Model\Group\Command\CreateGroupCommand;
use Prooph\ProophessorDo\Model\Group\GroupCollection;
use Prooph\ProophessorDo\Model\Group\Group;

class CreateGroupHandler
{
    /**
     * @var GroupCollection
     */
    private $groupCollection;

    public function __construct(
        GroupCollection $groupCollection
    ) {
        $this->groupCollection = $groupCollection;
    }

    public function __invoke(CreateGroupCommand $command): void
    {
//        if ($userId = ($this->checksUniqueUsersEmailAddress)($command->emailAddress())) {
//            if (! $user = $this->userCollection->get($userId)) {
//                throw UserNotFound::withUserId($userId);
//            }
//
//            $user->registerAgain($command->name());
//        } else {
//            if ($user = $this->userCollection->get($command->userId())) {
//                throw UserAlreadyExists::withUserId($command->userId());
//            }
//            $user = Friend::registerWithData($command->userId(), $command->name(), $command->emailAddress());
//        }
        $group = Group::createGroup($command->groupId(), $command->owner(), $command->members());


        $this->groupCollection->save($group);
    }
}
