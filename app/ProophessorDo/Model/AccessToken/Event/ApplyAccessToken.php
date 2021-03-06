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
     * @var TokenId
     */
    private $tokenId;

    private $tokenableType;
    /**
     * @var UserId
     */
    private $tokenableId;
    private $name;
    private $token;
    private $abilities;

    public static function withData(
        TokenId $tokenId,
        UserId $tokenableId,
        $name,
        $token,
        $tokenableType,
        $abilities): ApplyAccessToken
    {
        /** @var self $event */
        $event = self::occur($tokenId->toString(), [
            'tokenable_id' => $tokenableId->toString(),
            'name' => $name,
            'token' => $token,
            'abilities' => $abilities,
            'tokenable_type' => $tokenableType,
        ]);

        $event->tokenId = $tokenId;
        $event->tokenableId = $tokenableId;
        $event->name = $name;
        $event->token = $token;
        $event->abilities = $abilities;
        $event->tokenableType = $tokenableType;

        return $event;
    }

    public function tokenId(): TokenId
    {
        if (null === $this->tokenId) {
            $this->tokenId = TokenId::fromString($this->aggregateId());
        }

        return $this->tokenId;
    }
    public function tokenableId(): UserId
    {
        if (null === $this->tokenableId) {
            $this->tokenableId = UserId::fromString($this->payload['tokenable_id']);
        }
        return $this->tokenableId;
    }
    public function tokenableType(): string
    {
        if (null === $this->tokenableType) {
            $this->tokenableType = $this->payload['tokenable_type'];
        }
        return $this->tokenableType;
    }
    public function name(): string
    {
        if (null === $this->name) {
            $this->name = $this->payload['name'];
        }
        return $this->name;
    }

    public function token(): string
    {
        if (null === $this->token) {
            $this->token = $this->payload['token'];
        }
        return $this->token;
    }
    public function abilities(): array
    {
        if (null === $this->abilities) {
            $this->abilities = $this->payload['abilities'];
        }
        return $this->abilities;
    }
}
