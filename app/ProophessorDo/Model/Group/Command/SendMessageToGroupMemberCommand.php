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
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageText;

final class SendMessageToGroupMemberCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $groupId, string $messageText, string $messageId): SendMessageToGroupMemberCommand
    {
        return new self([
            'group_id' => $groupId,
            'message_text' => $messageText,
            'message_id' => $messageId,
        ]);
    }

    public function groupId(): GroupId
    {
        return GroupId::fromString($this->payload['group_id']);
    }

    public function messageId(): MessageId
    {
        return MessageId::fromString($this->payload['message_id']);
    }

    public function messageText(): MessageText
    {
        return MessageText::fromString($this->payload['message_text']);
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'group_id');
        Assertion::uuid($payload['group_id']);
        Assertion::keyExists($payload, 'message_id');
        Assertion::uuid($payload['message_id']);
        Assertion::keyExists($payload, 'message_text');
        Assertion::string($payload['message_text']);

        $this->payload = $payload;
    }
}
