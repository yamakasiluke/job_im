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

namespace Prooph\ProophessorDo\Model\Group;

use Illuminate\Support\Facades\App;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\ProophessorDo\Model\Entity;
use Prooph\ProophessorDo\Model\Group\Command\CreateGroupCommand;
use Prooph\ProophessorDo\Model\Group\Event\CreateGroup;
use Prooph\ProophessorDo\Model\Group\Event\EnterGroup;
use Prooph\ProophessorDo\Model\Group\Event\SendMessageToGroupMember;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageText;
use Prooph\ProophessorDo\Model\User\UserId;
use SwooleTW\Http\Server\Facades\Server;
use SwooleTW\Http\Websocket\Facades\Websocket;

final class Group extends AggregateRoot implements Entity
{
    /**
     * @var GroupId
     */
    private $groupId;

    /**
     * @var UserId
     */
    private $owner;

    /**
     * @var array
     * userid->tostring
     */
    private $members;

    /**
     * @var \DateTime
     */
    private $lastTime;

    /**
     * @var MessageText
     */
    private $lastMessage;


    public function onlineMembers(): array{
        return $this->members;
    }

    protected function WhenCreateGroup(CreateGroup $event): void {
        $this->groupId = $event->groupId();
        $this->owner = $event->owner();
        $this->members = $event->members();
    }

    public function InviteToGroup():void{}
    protected function WhenInviteToGroup(): void {}

    public function QuitGroup():void{}
    protected function WhenQuitGroup(): void {}

    protected function WhenEnterGroup(EnterGroup $event): void {
        $this->groupId = $event->groupId();
        $this->members = array_unique(array_merge($this->members, $event->members()));
    }
    protected function WhenSendMessageToGroupMember(SendMessageToGroupMember $event): void {
        $this->lastMessage = $event->messageText();

    }


    public function ExitGroup():void{}
    protected function WhenExitGroup(): void {}

    public function ChangeGroupOwner():void{}
    protected function WhenChangeGroupOwner(): void {}

    public static function createGroup(
        GroupId $groupId,
        UserId $owner,
        array $members
    ): Group {
        $self = new self();

        $self->recordThat(CreateGroup::withData($groupId, $owner, $members));

        return $self;
    }

    public function enterGroup(GroupId $groupId,array $members): void
    {
        $newMembers = array_diff($members, $this->members);

        $this->recordThat(EnterGroup::withData($groupId, $newMembers));
    }

    public function sendMessageToGroupMember(GroupId $groupId, MessageId $messageId, MessageText $messageText, array $members): void
    {
        $server = App::make(Server::class);
        if(isset($server)){
            foreach ($members as $id => $fd) {
                var_dump($members);
                var_dump($fd, $messageText->toString());
                // 需要先判断是否是正确的websocket连接，否则有可能会push失败
                if ($server->isEstablished($fd)) {
                    $server->push($fd, $messageText->toString());
                }
            }

        }

//            $server->push($this->fd, "herere send buy event");
        $this->recordThat(SendMessageToGroupMember::withData($groupId, $messageId, $messageText));
    }

    public function registerAgain(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function groupId(): GroupId
    {
        return $this->groupId;
    }

    public function owner(): UserId
    {
        return $this->owner;
    }

    public function name(): UserName
    {
        return $this->name;
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
        return $this->groupId->toString();
    }

    protected function whenUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->name = $event->name();
        $this->emailAddress = $event->emailAddress();
    }

    protected function whenUserWasRegisteredAgain(BlockFriend $event): void
    {
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
