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

namespace Prooph\ProophessorDo\Model\want\want_buy_goods\handler;

use Prooph\ProophessorDo\Model\Todo\TodoList;
use Prooph\ProophessorDo\Model\want\want_buy_goods\commandProoph\ProophessorDo\Model\Todo\Command\AddDeadlineToTodo;
use Prooph\ProophessorDo\Model\want\want_buy_goods\command\update_want_buy_goods_command;
use Prooph\ProophessorDo\Model\want\want_buy_goods\want_buy_goods;
use Prooph\ProophessorDo\Model\want\want_buy_goods\want_buy_goods_list;

class update_want_buy_goods_handler
{
    /**
     * @var TodoList
     */
    private $goodslist;


    public function __construct(want_buy_goods_list $goodslist)
    {
        $this->goodslist = $goodslist;
    }

    public function __invoke(update_want_buy_goods_command $command): void
    {
        $w = want_buy_goods::registerWithData($command->a());
//        $goods = $this->goodslist->get($command->a());
        $w->whenUserWasRegisteredAgain();
//        $todo = $this->todoList->get($command->a());
//        $todo->addDeadline($command->userId(), $command->deadline());
//
        $this->goodslist->save($w);
    }
}

//public function __construct(TodoList $todoList)
//{
//    $this->todoList = $todoList;
//}
//
//public function __invoke(AddDeadlineToTodo $command): void
//{
//    $todo = $this->todoList->get($command->todoId());
//    $todo->addDeadline($command->userId(), $command->deadline());
//
//    $this->todoList->save($todo);
//}
