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

namespace Prooph\ProophessorDo\Model\Good\Handler;

use Prooph\ProophessorDo\Model\Good\Command\PublishGoodCommand;
use Prooph\ProophessorDo\Model\Good\Good;
use Prooph\ProophessorDo\Model\Good\GoodId;
use Prooph\ProophessorDo\Model\Good\Goods;

class PublishGoodHandler
{
    /**
     * @var Goods
     */
    private $goods;

    public function __construct(Goods $goods)
    {
        $this->goods = $goods;
    }

    public function __invoke(PublishGoodCommand $command): void
    {

//        $good = $this->goods->get($command->goodId());
        $good = Good::publish(GoodId::generate());

        $this->goods->save($good);
    }
}
