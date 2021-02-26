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

namespace Prooph\ProophessorDo\Model\Group\Event;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageText;
use Prooph\ProophessorDo\Model\User\UserId;

final class  SendMessageToGroupMember extends AggregateChanged
{
    /**
     * @var GroupId
     */
    private $groupId;

    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var MessageText
     */
    private $messageText;

    public static function withData(GroupId $groupId, MessageId $messageId, MessageText $messageText): SendMessageToGroupMember
    {
        /** @var self $event */
        $event = self::occur($groupId->toString(), [
            'message_id' => $messageId->toString(),
            'message_text' => $messageText->toString(),
        ]);

        $event->groupId = $groupId;
        $event->messageId = $messageId;
        $event->messageText = $messageText;
        return $event;
    }

    public function groupId(): GroupId
    {
        if (null === $this->groupId) {
            $this->groupId = GroupId::fromString($this->aggregateId());
        }

        return $this->groupId;
    }

    public function messageId(): MessageId
    {
        if (null === $this->messageId) {
            $this->messageId = MessageId::fromString($this->payload['message_id']);
        }

        return $this->messageId;
    }

    public function messageText(): MessageText
    {
        if (null === $this->messageText) {
            $this->messageText = MessageText::fromString($this->payload['message_text']);
        }

        return $this->messageText;
    }



}
