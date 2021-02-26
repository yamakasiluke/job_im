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

namespace Prooph\ProophessorDo\Model\AccessToken\Event;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\AccessToken\TokenId;
use Prooph\ProophessorDo\Model\User\UserId;

final class  ApplyAccessToken extends AggregateChanged
{
    /**
     * @var UserId
     */
    private $userId;
    /**
     * @var TokenId
     */
    private $tokenId;

    /**
     * @var AccessToken
     */
    private $accessToken;

    // {
    //      data {}
    //      status 200 500
    //      message ""
    //  }
    // token
    // userid?
    public static function withData(UserId $userId, TokenId $tokenId): ApplyAccessToken
    {
        /** @var self $event */
        $event = self::occur($tokenId->toString(), [
            'user_id' => $userId->toString(),
        ]);

        $event->userId = $userId;
        $event->tokenId = $tokenId;

        return $event;
    }

    public function tokenId(): TokenId
    {
        if (null === $this->tokenId) {
            $this->tokenId = TokenId::fromString($this->aggregateId());
        }

        return $this->tokenId;
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
