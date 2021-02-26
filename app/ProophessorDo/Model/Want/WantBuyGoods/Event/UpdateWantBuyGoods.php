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

namespace Prooph\ProophessorDo\Model\want\want_buy_goods\event;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\User\UserName;

final class update_want_buy_goods_event extends AggregateChanged
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var UserName
     */
    private $username;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    public static function withData(): update_want_buy_goods_event
    {
        /** @var self $event */
        $event = self::occur("12", [
            'name' => "look",
        ]);

        $event->userId = "12";

        return $event;
    }

    public function userId(): UserId
    {
        if (null === $this->userId) {
            $this->userId = UserId::fromString($this->aggregateId());
        }

        return $this->userId;
    }

    public function name(): UserName
    {
        if (null === $this->username) {
            $this->username = UserName::fromString($this->payload['name']);
        }

        return $this->username;
    }

    public function emailAddress(): EmailAddress
    {
        if (null === $this->emailAddress) {
            $this->emailAddress = EmailAddress::fromString($this->payload['email']);
        }

        return $this->emailAddress;
    }
}
