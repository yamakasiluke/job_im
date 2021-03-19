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

namespace Prooph\ProophessorDo\Model\Message\Handler;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Prooph\ProophessorDo\Model\Message\Command\SendMessageToGroupCommand;
use Prooph\ProophessorDo\Model\Message\Event\SendMessageToGroup;
use Prooph\ProophessorDo\Model\Message\Exception\UserAlreadyExists;
use Prooph\ProophessorDo\Model\Message\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\Message\Service\ChecksUniqueUsersEmailAddress;
use Prooph\ProophessorDo\Model\Message\Message;
use Prooph\ProophessorDo\Model\Message\MessageList;
use Prooph\ProophessorDo\Model\User\Exception\UserNotUseIm;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ServiceBus\EventBus;

class SendMessageToGroupHandler
{
    /**
     * @var MessageList
     */
    private $messageList;
    /**
     * @var EventBus
     */
    private $eventBus;

    public function __construct(
        MessageList $messageList,
        EventBus $eventBus
    ) {
        $this->messageList = $messageList;
        $this->eventBus = $eventBus;
    }
    //'sender' => Sender,
//'receiver' => $receiver->toString(),
//'sender_id' => $receiver->toString(),
//'receiver_id' => $receiverId->toString(),
//'message_text' => $messageText->toString(),

    public function __invoke(SendMessageToGroupCommand $command): void
    {
//        if (!App::environment('testing')) {
//            global $server;
//            if(!isset($server))
//                throw UserNotUseIm::withUserId(
//                    UserId::fromString(Auth::id()));
//        }

        $message = Message::createMessage(
            $command->messageId(),
            $command->sender(),
            $command->senderId(),
            $command->receiver(),
            $command->receiverId(),
            $command->messageText()
        );
        // TODO:  do some validate
//        senderId is userid self
//        receiverId is groupid valid
        $this->messageList->save($message);
        $this->eventBus->dispatch(SendMessageToGroup::withData($command->messageId(),
            $command->sender(),
            $command->senderId(),
            $command->receiver(),
            $command->receiverId(),
            $command->messageText()));
    }
}
