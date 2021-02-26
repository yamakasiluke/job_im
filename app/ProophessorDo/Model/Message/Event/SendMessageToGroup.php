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

namespace Prooph\ProophessorDo\Model\Message\Event;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageText;
use Prooph\ProophessorDo\Model\Message\Receiver;
use Prooph\ProophessorDo\Model\Message\Sender;
use Prooph\ProophessorDo\Model\User\UserId;

final class SendMessageToGroup extends AggregateChanged
{
    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var \Prooph\ProophessorDo\Model\User\UserId
     */
    private $senderId;


    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * @var GroupId
     */
    private $receiverId;

    /**
     * @var MessageText
     */
    private $messageText;


//   status

    public static function withData(
        MessageId $messageId,
        Sender $sender,
        UserId $senderId,
        Receiver $receiver,
        GroupId $receiverId,
        MessageText $messageText
    ): SendMessageToGroup
    {
        /** @var self $event */
        $event = self::occur($messageId->toString(), [
            'sender' => $sender->toString(),
            'receiver' => $receiver->toString(),
            'sender_id' => $senderId->toString(),
            'receiver_id' => $receiverId->toString(),
            'message_text' => $messageText->toString(),
        ]);

        $event->messageId = $messageId;
        $event->sender = $sender;
        $event->senderId = $senderId;
        $event->receiver = $receiver;
        $event->receiverId = $receiverId;
        $event->messageText = $messageText;

        return $event;
    }

    public function messageId(): MessageId
    {
        if (null === $this->messageId) {
            $this->messageId = MessageId::fromString($this->aggregateId());
        }

        return $this->messageId;
    }

    public function sender(): Sender
    {
        if (null === $this->sender) {
            $this->sender = Sender::byName($this->payload['sender']);
        }

        return $this->sender;
    }
    public function receiver(): receiver
    {
        if (null === $this->receiver) {
            $this->receiver = receiver::byName($this->payload['receiver']);
        }

        return $this->receiver;
    }
    public function senderId(): UserId
    {
        if (null === $this->senderId) {
            $this->senderId = UserId::fromString($this->payload['sender_id']);
        }

        return $this->senderId;
    }
    public function receiverId(): GroupId
    {
        if (null === $this->receiverId) {
            $this->receiverId = GroupId::fromString($this->payload['receiver_id']);
        }

        return $this->receiverId;
    }

    public function messageText(): MessageText
    {
        if (null === $this->messageText) {
            $this->messageText = UserId::fromString($this->payload['message_text']);
        }

        return $this->messageText;
    }


}
