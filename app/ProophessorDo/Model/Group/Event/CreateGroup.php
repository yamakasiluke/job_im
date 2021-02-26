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

namespace Prooph\ProophessorDo\Model\Group\Event;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\User\UserId;

final class  CreateGroup extends AggregateChanged
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
     * @var UserId[]
     */
    private $members;

    public static function withData(GroupId $groupId, UserId $owner, array $members): CreateGroup
    {
        /** @var self $event */
        $event = self::occur($groupId->toString(), [
            'owner' => $owner->toString(),
            'members' => $members,
        ]);

        $event->groupId = $groupId;
        $event->owner = $owner;
        $event->members = $members;

        return $event;
    }

    public function groupId(): GroupId
    {
        if (null === $this->groupId) {
            $this->groupId = GroupId::fromString($this->aggregateId());
        }

        return $this->groupId;
    }

    public function owner(): UserId
    {
        if (null === $this->owner) {
            $this->owner = UserId::fromString($this->payload['owner']);
        }

        return $this->owner;
    }

    public function members(): array
    {
        if (null === $this->members) {
            $this->members = $this->payload['members'];
        }

        return $this->members;
    }


}
