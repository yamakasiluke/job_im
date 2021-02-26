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

use Prooph\ProophessorDo\Model\Message\Command\SendMessageToGroupCommand;
use Prooph\ProophessorDo\Model\Message\Exception\UserAlreadyExists;
use Prooph\ProophessorDo\Model\Message\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\Message\Service\ChecksUniqueUsersEmailAddress;
use Prooph\ProophessorDo\Model\Message\Message;
use Prooph\ProophessorDo\Model\Message\MessageList;

class SendMessageToGroupHandler
{
    /**
     * @var MessageList
     */
    private $messageList;

    public function __construct(
        MessageList $messageList
    ) {
        $this->messageList = $messageList;
    }
    //'sender' => Sender,
//'receiver' => $receiver->toString(),
//'sender_id' => $receiver->toString(),
//'receiver_id' => $receiverId->toString(),
//'message_text' => $messageText->toString(),

    public function __invoke(SendMessageToGroupCommand $command): void
    {
        $message = Message::createMessage(
            $command->messageId(),
            $command->sender(),
            $command->senderId(),
            $command->receiver(),
            $command->receiverId(),
            $command->messageText()
        );

        $this->messageList->save($message);
    }
}
