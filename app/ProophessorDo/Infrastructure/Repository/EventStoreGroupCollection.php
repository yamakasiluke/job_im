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

namespace Prooph\ProophessorDo\Infrastructure\Repository;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\ProophessorDo\Model\Group\Group;
use Prooph\ProophessorDo\Model\Group\GroupCollection;
use Prooph\ProophessorDo\Model\Group\GroupId;

final class EventStoreGroupCollection extends AggregateRepository implements GroupCollection
{
    public function save(Group $group): void
    {
        $this->saveAggregateRoot($group);
    }

    public function get(GroupId $groupId): ?Group
    {
        return $this->getAggregateRoot($groupId->toString());
    }
}
