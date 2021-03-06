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

use http\Message;
use Prooph\ProophessorDo\Model\User\Command\RegisterUserCommand;
use Prooph\ProophessorDo\Model\User\Event\RegisterUser;
use Prooph\ProophessorDo\Model\User\Exception\UserAlreadyExists;
use Prooph\ProophessorDo\Model\User\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\User\Service\ChecksUniqueUsersEmailAddress;
use Prooph\ProophessorDo\Model\User\User;
use Prooph\ProophessorDo\Model\User\UserCollection;
use Prooph\ServiceBus\EventBus;

class RegisterUserHandler
{
    /**
     * @var UserCollection
     */
    private $userCollection;

    /**
     * @var ChecksUniqueUsersEmailAddress
     */
    private $checksUniqueUsersEmailAddress;
    /**
     * @var EventBus
     */
    private $eventBus;

    public function __construct(
        UserCollection $userCollection,
        ChecksUniqueUsersEmailAddress $checksUniqueUsersEmailAddress,
        EventBus $eventBus
    ) {
        $this->userCollection = $userCollection;
        $this->checksUniqueUsersEmailAddress = $checksUniqueUsersEmailAddress;
        $this->eventBus = $eventBus;
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        if ($userId = ($this->checksUniqueUsersEmailAddress)($command->emailAddress())) {
            if (! $user = $this->userCollection->get($userId)) {
                throw UserNotFound::withUserId($userId);
            }
            $user->registerAgain($command->name());
        } else {
            if ($user = $this->userCollection->get($command->userId())) {
                throw UserAlreadyExists::withUserId($command->userId());
            }
            $user = User::registerWithData($command->userId(), $command->name(), $command->emailAddress(), $command->password());
            $this->eventBus->dispatch(RegisterUser::withData($command->userId(), $command->name(), $command->emailAddress(), $command->password()));
        }

        $this->userCollection->save($user);

    }
}
