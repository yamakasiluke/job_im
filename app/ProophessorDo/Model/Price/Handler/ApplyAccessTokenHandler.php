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

namespace Prooph\ProophessorDo\Model\User\Event;

use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\User\UserName;

final class  ApplyAccessToken extends AggregateChanged
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

    public static function withData(UserId $userId, UserName $name, EmailAddress $emailAddress): UserWasRegistered
    {
        /** @var self $event */
        $event = self::occur($userId->toString(), [
            'name' => $name->toString(),
            'email' => $emailAddress->toString(),
        ]);

        $event->userId = $userId;
        $event->username = $name;
        $event->emailAddress = $emailAddress;

        return $event;
    }

    public function userId(): UserId
    {


//        $token = $this->tokens()->create([
//            'name' => $name,
//            'token' => hash('sha256', $plainTextToken = Str::random(40)),
//            'abilities' => $abilities,
//        ]);
//
//        return new NewAccessToken($token, $token->id.'|'.$plainTextToken);
//

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
