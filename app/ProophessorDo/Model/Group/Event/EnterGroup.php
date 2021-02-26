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

final class  EnterGroup extends AggregateChanged
{
    /**
     * @var GroupId
     */
    private $groupId;

    /**
     * @var UserId[]
     */
    private $members;

    public static function withData(GroupId $groupId, array $members): EnterGroup
    {
        /** @var self $event */
        $event = self::occur($groupId->toString(), [
            'members' => $members,
        ]);

        $event->groupId = $groupId;
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


    public function members(): array
    {
        if (null === $this->members) {
            $this->members = $this->payload['members'];
        }

        return $this->members;
    }


}
