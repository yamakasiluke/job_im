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

namespace Prooph\ProophessorDo\Model\want\want_buy_goods\command;

use Prooph\Common\Messaging;
use Prooph\ProophessorDo\Model\Todo\TodoDeadline;
use Prooph\ProophessorDo\Model\Todo\TodoId;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\want\want_buy_goods\handler\update_want_buy_goods_handler;

final class update_want_buy_goods_command extends Messaging\Command implements Messaging\PayloadConstructable
{
    use Messaging\PayloadTrait;

    public function a(): string
    {
        return $this->payload['user_id'];
    }

//    public function todoId(): TodoId
//    {
//        return TodoId::fromString($this->payload['todo_id']);
//    }
//
//    public function deadline(): TodoDeadline
//    {
//        return TodoDeadline::fromString($this->payload['deadline']);
//    }
}
