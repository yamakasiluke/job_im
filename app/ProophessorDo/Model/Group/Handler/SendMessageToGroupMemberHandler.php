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

use Prooph\ProophessorDo\Model\Group\Command\SendMessageToGroupMemberCommand;
use Prooph\ProophessorDo\Model\Group\GroupCollection;
use Prooph\ProophessorDo\Model\User\Service\ChecksUniqueUsersEmailAddress;
use Prooph\ProophessorDo\Model\User\UserCollection;
use Prooph\ProophessorDo\Model\User\UserId;

class SendMessageToGroupMemberHandler
{

    /**
     * @var GroupCollection
     */
    private $groupCollection;
    /**
     * @var UserCollection
     */
    private $userCollection;
    public function __construct(
        GroupCollection $groupCollection,
        UserCollection $userCollection
    ) {
        $this->groupCollection = $groupCollection;
        $this->userCollection = $userCollection;
    }

    public function __invoke(SendMessageToGroupMemberCommand $command): void
    {
//        if ($userId = ($this->checksUniqueUsersEmailAddress)($command->emailAddress())) {
//            if (! $user = $this->userCollection->get($userId)) {
//                throw UserNotFound::withUserId($userId);
//            }
//
//            $user->registerAgain($command->name());
//        } else {
//        }
//        if ($group = $this->groupCollection->get($command->groupId())) {
//            throw UserAlreadyExists::withUserId($command->groupId());
//        }
        $group = $this->groupCollection->get($command->groupId());
        // TODO: userfinder this is slow
        $members = [];
        foreach ($group->onlineMembers() as $id){
            $user = $this->userCollection->get(UserId::fromString($id));
            if($user->isOnline())
                $members[$id] = $user->fd();
        }

        $group->sendMessageToGroupMember($command->groupId(), $command->messageId(),  $command->messageText(), $members);


        $this->groupCollection->save($group);
    }
}
