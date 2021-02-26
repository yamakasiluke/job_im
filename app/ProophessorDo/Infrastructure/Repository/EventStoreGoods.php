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
use Prooph\ProophessorDo\Model\Good\Good;
use Prooph\ProophessorDo\Model\Good\GoodId;
use Prooph\ProophessorDo\Model\Good\Goods;

final class EventStoreGoods extends AggregateRepository implements Goods
{
    public function save(Good $user): void
    {
        $this->saveAggregateRoot($user);
    }

    public function get(GoodId $userId): ?Good
    {
        return $this->getAggregateRoot($userId->toString());
    }
}
