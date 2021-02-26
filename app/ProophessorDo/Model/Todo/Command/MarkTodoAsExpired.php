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

final class MarkTodoAsExpired extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function forTodo(string $todoId): MarkTodoAsExpired
    {
        Assertion::uuid($todoId);

        return new self([
            'todo_id' => $todoId,
        ]);
    }

    public function todoId(): TodoId
    {
        return TodoId::fromString($this->payload['todo_id']);
    }
}
