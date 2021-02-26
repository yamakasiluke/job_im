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

namespace Prooph\ProophessorDo\Model\Todo\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ProophessorDo\Model\Todo\TodoId;
use Prooph\ProophessorDo\Model\Todo\TodoReminder;
use Prooph\ProophessorDo\Model\Todo\TodoReminderStatus;

final class RemindTodoAssignee extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function forTodo(TodoId $todoId, TodoReminder $todoReminder): RemindTodoAssignee
    {
        return new self([
            'todo_id' => $todoId->toString(),
            'reminder' => $todoReminder->toString(),
            'reminder_status' => $todoReminder->status()->toString(),
        ]);
    }

    public function todoId(): TodoId
    {
        return TodoId::fromString($this->payload['todo_id']);
    }

    public function reminder(): TodoReminder
    {
        return TodoReminder::from($this->payload['reminder'], $this->payload['reminder_status']);
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'todo_id');
        Assertion::uuid($payload['todo_id']);
        Assertion::keyExists($payload, 'reminder');
        Assertion::string($payload['reminder']); // @todo: check for date format
        Assertion::keyExists($payload, 'reminder_status');
        Assertion::true(defined(TodoReminderStatus::class . '::' . $payload['reminder_status']));

        $this->payload = $payload;
    }
}
