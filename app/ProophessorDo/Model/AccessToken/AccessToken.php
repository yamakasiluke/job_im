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

    /**
     * @var TokenId
     */
    private $tokenId;

    private $tokenableType;

    private $tokenableId;
    private $name;
    private $token;
    private $abilities;



    public static function applyToken(
        TokenId $tokenId,
        UserId $tokenableId,
        $name,
        $token,
        $tokenableType,
        $abilities
    ): AccessToken {

//        $request->validate([
////        'email' => 'required|email',
////        'password' => 'required',
////        'device_name' => 'required',
////    ]);

        $self = new self();

        $self->recordThat(ApplyAccessToken::withData(
            $tokenId,
            $tokenableId,
            $name,
            $token,
            $tokenableType,
            $abilities));

        return $self;
    }

    protected function aggregateId(): string
    {
        return $this->tokenId->toString();
    }

    protected function whenApplyAccessToken(ApplyAccessToken $event): void
    {
        $this->tokenId = $event->tokenId();
        $this->tokenableId = $event->tokenableId();
        $this->tokenableType = $event->tokenableType();
        $this->name = $event->name();
        $this->token = $event->token();
        $this->abilities = $event->abilities();
    }


    public function sameIdentityAs(Entity $other): bool
    {
        return get_class($this) === get_class($other) && $this->tokenId->sameValueAs($other->tokenId);
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
