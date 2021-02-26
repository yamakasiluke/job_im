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
namespace Prooph\ProophessorDo\Model\want\want_buy_goods;

use phpDocumentor\Reflection\Types\String_;
use Prooph\ProophessorDo\Model\User\UserId;

interface want_buy_goods_list
{
    public function save(want_buy_goods $goods): void;

    public function get(string $userId): ?want_buy_goods;
}
