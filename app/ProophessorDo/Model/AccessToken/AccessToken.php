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

namespace Prooph\ProophessorDo\Model\AccessToken;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\ProophessorDo\Model\AccessToken\Event\ApplyAccessToken;
use Prooph\ProophessorDo\Model\Entity;
use Prooph\ProophessorDo\Model\User\UserId;


final class AccessToken extends AggregateRoot implements Entity
{

//uuid('id');
//morphs('tokenable');
//string('name');
//string('token', 64)->unique();
//text('abilities')->nullable();
//timestamp('last_used_at')->nullable();
//timestamps();

    /**
     * @var TokenId
     */
    private $tokenId;

    /**
     * @var DeviceName
     */
    private $deviceName;


    /**
     * @var AccessToken
     */
    private $accessToken;


    public static function applyToken(
        UserId $userId,
        TokenId $tokenId
    ): AccessToken {

//
//        $request->validate([
////        'email' => 'required|email',
////        'password' => 'required',
////        'device_name' => 'required',
////    ]);

        $self = new self();

        $self->recordThat(ApplyAccessToken::withData($userId, $tokenId));

        return $self;
    }

    public function registerAgain(UserName $name): void
    {
        $this->recordThat(UserWasRegisteredAgain::withData($this->userId, $name, $this->emailAddress));
    }

    public function userLogin(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function userLogout(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function userOnline(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
    }

    public function userOffline(UserName $name): void
    {
        $this->recordThat(BlockFriend::withData($this->userId, $name, $this->emailAddress));
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
        return $this->tokenId->toString();
    }

    protected function whenApplyAccessToken(ApplyAccessToken $event): void
    {
        $this->userId = $event->userId();
        $this->tokenId = $event->tokenId();
    }

    protected function whenUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->name = $event->name();
        $this->emailAddress = $event->emailAddress();
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
