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

namespace Prooph\ProophessorDo\Model\Group\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\User\UserId;

final class CreateGroupCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $groupId, string $owner, string $members): CreateGroupCommand
    {
        return new self([
            'group_id' => $groupId,
            'owner' => $owner,
            'members' => $members,
        ]);
    }

    public function groupId(): GroupId
    {
        return GroupId::generate();
    }

    public function owner(): UserId
    {
        return UserId::fromString($this->payload['owner']);
    }

    public function members(): array
    {
        return $this->payload['members'];
    }

    protected function setPayload(array $payload): void
    {
//        Assertion::keyExists($payload, 'group_id');
//        Assertion::uuid($payload['group_id']);
        Assertion::keyExists($payload, 'owner');
        Assertion::uuid($payload['owner']);

        $this->payload = $payload;
    }
}
