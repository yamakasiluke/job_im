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

namespace Prooph\ProophessorDo\Model\Good\Event;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\ProophessorDo\Model\Good\GoodId;

final class PublishGoodEvent extends AggregateChanged
{
    /**
     * @var GoodId
     */
    private $goodId;

    public static function publish(GoodId $goodId): PublishGoodEvent
    {
        /** @var self $event */
        $event = self::occur($goodId->toString(), [
            'good_id' => $goodId->toString(),
        ]);

        $event->goodId = $goodId;

        return $event;
    }

    public function goodId(): GoodId
    {
        if (null === $this->goodId) {
            $this->goodId = GoodId::generate();
        }

        return $this->goodId;
    }


}
