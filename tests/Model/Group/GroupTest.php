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

namespace Tests\Model;

use Prooph\ProophessorDo\Model\Group\Event\CreateGroup;
use Prooph\ProophessorDo\Model\Group\Event\EnterGroup;
use Prooph\ProophessorDo\Model\Group\Group;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\User\UserId;
use Tests\TestCase;

class GroupTest extends TestCase
{
    /**
     * @test
     */
    public function it_random_user_create_a_group(): Group
    {
        $userId = UserId::generate();
        $groupId = GroupId::generate();
        $members = [$userId->toString()];
        $group = Group::createGroup($groupId, $userId, $members);

        $this->assertInstanceOf(Group::class, $group);
        $events = $this->popRecordedEvent($group);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(CreateGroup::class, $events[0]);

        $expectedPayload = [
            'owner' => $userId->toString(),
            'members' => $members,
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        return $group;


    }
    /**
     * @test
     */
    public function it_random_user_enter_a_group(): Group
    {

        $userId = UserId::generate()->toString();
        $members = [$userId];
        $group = $this->it_random_user_create_a_group();
        $group->enterGroup($group->groupId(), $members);

        $this->assertInstanceOf(Group::class, $group);
        $events = $this->popRecordedEvent($group);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(EnterGroup::class, $events[0]);

        $expectedPayload = [
            'members' => $members,
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        return $group;
    }

}

