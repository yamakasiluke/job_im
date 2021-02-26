<?php

/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\ProophessorDo\Model\want\want_buy_goods;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\ProophessorDo\Model\Entity;
use Prooph\ProophessorDo\Model\want\want_buy_goods\event\update_want_buy_goods_event;
use Prooph\ProophessorDo\Model\Todo\Todo;
use Prooph\ProophessorDo\Model\Todo\TodoId;
use Prooph\ProophessorDo\Model\Todo\TodoText;
use Prooph\ProophessorDo\Model\User\Event\UserWasRegistered;
use Prooph\ProophessorDo\Model\User\Event\BlockFriend;

final class Services  implements Entity
{

    private $want_buy_goods_id;
    private $title;
    private $description;
    private $price;
//    private $gps[];
//    private $ip[];
//    private $commnets[];
    private $deadline;
    private $pushlish_time;
    private $retweets;
    private $commissions;

    public static function registerWithData(
        string $userId
    ): want_buy_goods {
        $self = new self();
        $self->want_buy_goods_id = "12";
//        $self->recordThat(UserWasRegistered::withData($$userId));

        return $self;
    }

//    public function registerAgain(UserName $name): void
//    {
//        $this->recordThat(UserWasRegisteredAgain::withData($this->userId, $name, $this->emailAddress));
//    }
//
//    public function userId(): UserId
//    {
//        return $this->userId;
//    }
//
//    public function name(): UserName
//    {
//        return $this->name;
//    }
//
//    public function emailAddress(): EmailAddress
//    {
//        return $this->emailAddress;
//    }
//
//    public function postTodo(TodoText $text, TodoId $todoId): Todo
//    {
//        return Todo::post($text, $this->userId(), $todoId);
//    }
//
    protected function aggregateId(): string
    {
        return $this->want_buy_goods_id;
    }
//
    protected function whenupdate_want_buy_goods_event(): void
    {
//        $this->userId = $event->userId();
//        $this->name = $event->name();
//        $this->emailAddress = $event->emailAddress();
    }
//
    public function whenUserWasRegisteredAgain(): void
    {
        $this->recordThat(update_want_buy_goods_event::withData());
//        var_dump("asdf");
    }
//
    public function sameIdentityAs(Entity $other): bool
    {
        return \get_class($this) === \get_class($other) && $this->userId->sameValueAs($other->userId);
    }
//
    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);

        if (! \method_exists($this, $handler)) {
            throw new \RuntimeException(\sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                \get_class($this)
            ));
        }

        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'when' . \implode(\array_slice(\explode('\\', \get_class($e)), -1));
    }
}
