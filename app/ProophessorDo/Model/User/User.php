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

namespace Prooph\ProophessorDo\Model\User;

use Illuminate\Support\Facades\Hash;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\ProophessorDo\Model\Entity;
use Prooph\ProophessorDo\Model\Todo\Todo;
use Prooph\ProophessorDo\Model\Todo\TodoId;
use Prooph\ProophessorDo\Model\Todo\TodoText;
use Prooph\ProophessorDo\Model\User\Event\RegisterUser;
use Prooph\ProophessorDo\Model\User\Event\UserLogin;
use Prooph\ProophessorDo\Model\User\Event\UserOffline;
use Prooph\ProophessorDo\Model\User\Event\UserOnline;
use Prooph\ProophessorDo\Model\User\Event\UserWasRegisteredAgain;

final class User extends AggregateRoot implements Entity
{

    private $lastTime;
    private $lastNo;
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var UserName
     */
    private $name;
    /**
     * @var DeviceName
     */
    private $deviceName;

    /**
     * @var int
     */
    private $fd;

    /**
     * @var IsOnline
     */
    private $isOnline;


    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var EmailAddress
     */
    private $emailAddress;
    private $password;

    public static function registerWithData(
        UserId $userId,
        UserName $name,
        EmailAddress $emailAddress,
        $password
    ): User {
        $self = new self();
        $password = Hash::make($password);
        $self->recordThat(RegisterUser::withData($userId, $name, $emailAddress, $password));

        return $self;
    }

    public function registerAgain(UserName $name): void
    {
        $this->recordThat(UserWasRegisteredAgain::withData($this->userId, $name, $this->emailAddress));
    }

    public function userLogin(): void
    {
        $this->recordThat(UserLogin::withData($this->userId));
    }

    public function userLogout(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function userOnline(int $fd): void
    {
        $this->recordThat(UserOnline::withData($this->userId, $fd));
    }

    public function userOffline(): void
    {
        $this->recordThat(UserOffline::withData($this->userId));
    }


    public function userAnoymouse(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function applyAccessToken(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }



    public function userId(): UserId
    {
        return $this->userId;
    }

    public function name(): UserName
    {
        return $this->name;
    }
    public function fd(): int
    {
        return $this->fd;
    }
    public function isOnline(): bool
    {
        return isset($this->fd);
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
        return $this->userId->toString();
    }

    protected function whenRegisterUser(RegisterUser $event): void
    {
        $this->userId = $event->userId();
        $this->name = $event->name();
        $this->emailAddress = $event->emailAddress();
        $this->password = $event->password();

    }

    protected function whenUserOnline(UserOnline $event): void
    {
        $this->fd = $event->fd();
//
//        global $server;
//        if(isset($server))
//            $server->push($this->fd, "herere send buy event");
    }
    protected function whenUserLogin(): void
    {
    }

    protected function whenUserOffline(UserOffline $event): void
    {
//
    }



    protected function whenUserWasRegisteredAgain(UserWasRegisteredAgain $event): void
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
