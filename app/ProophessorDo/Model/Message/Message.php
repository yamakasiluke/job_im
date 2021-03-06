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

namespace Prooph\ProophessorDo\Model\Message;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\ProophessorDo\Model\Entity;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\Message\Event\SendMessageToGroup;
use Prooph\ProophessorDo\Model\Todo\Todo;
use Prooph\ProophessorDo\Model\Todo\TodoId;
use Prooph\ProophessorDo\Model\Todo\TodoText;
use Prooph\ProophessorDo\Model\User\UserId;

final class Message extends AggregateRoot implements Entity
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
     * @var UserId
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



    public static function createMessage(
        MessageId $messageId,
        Sender $sender,
        UserId $senderId,
        Receiver $receiver,
        GroupId $receiverId,
        MessageText $messageText
    ): Message {
        $self = new self();

        $self->recordThat(SendMessageToGroup::withData(
            $messageId,
            $sender,
            $senderId,
            $receiver,
            $receiverId,
            $messageText));

        return $self;
    }

    public function registerAgain(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function messageId(): MessageId
    {
        return $this->messageId;
    }

    public function messageText(): MessageText
    {
        return $this->messageText;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function postTodo(TodoText $text, TodoId $todoId): Todo
    {
        return Todo::post($text, $this->userId(), $todoId);
    }

    protected function aggregateId(): string
    {
        return $this->messageId->toString();
    }

    protected function whenUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->name = $event->name();
        $this->emailAddress = $event->emailAddress();
    }

    protected function whenSendMessageToGroup(SendMessageToGroup $event): void
    {
        $this->messageId  = $event->messageId();
        $this->sender  = $event->sender();
        $this->senderId  = $event->senderId();
        $this->receiver  = $event->receiver();
        $this->receiverId  = $event->receiverId();
        $this->messageText  = $event->messageText();
    }

    public function sameIdentityAs(Entity $other): bool
    {
        return get_class($this) === get_class($other) && $this->userId->sameValueAs($other->userId);
    }

    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);

        if (! method_exists($this, $handler)) {
            throw new \RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this)
            ));
        }

        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'when' . implode(array_slice(explode('\\', get_class($e)), -1));
    }
}
