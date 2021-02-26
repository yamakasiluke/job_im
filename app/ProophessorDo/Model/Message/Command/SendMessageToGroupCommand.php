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

namespace Prooph\ProophessorDo\Model\Message\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageText;
use Prooph\ProophessorDo\Model\Message\Receiver;
use Prooph\ProophessorDo\Model\Message\Sender;
use Prooph\ProophessorDo\Model\User\UserId;

final class SendMessageToGroupCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;
    // userid
    // meesage
    // groupid

//'sender' => Sender,
//'receiver' => $receiver->toString(),
//'sender_id' => $receiver->toString(),
//'receiver_id' => $receiverId->toString(),
//'message_text' => $messageText->toString(),

    public static function withData(string $messageText, string $senderId, string $receiverId): SendMessageToGroupCommand
    {
        return new self([
            'message_text' => $messageText,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
        ]);
    }
    public function messageId(): MessageId
    {
        return MessageId::generate();
    }

    public function sender(): Sender
    {
        return Sender::USER();
    }

    public function receiver(): Receiver
    {
        return Receiver::GROUP();
    }

    public function messageText(): MessageText
    {
        return MessageText::fromString($this->payload['message_text']);
    }

    public function senderId(): UserId
    {
        return UserId::fromString($this->payload['sender_id']);
    }

    public function receiverId(): GroupId
    {
        return GroupId::fromString($this->payload['receiver_id']);
    }


    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'sender_id');
        Assertion::uuid($payload['sender_id']);
        Assertion::keyExists($payload, 'receiver_id');
        Assertion::uuid($payload['receiver_id']);
        Assertion::keyExists($payload, 'message_text');
        Assertion::string($payload['message_text']);


        $this->payload = $payload;
    }
}
