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
use Prooph\ProophessorDo\Model\Message\Message;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageList;

final class EventStoreMessageList extends AggregateRepository implements MessageList
{
    public function save(Message $message): void
    {
        $this->saveAggregateRoot($message);
    }

    public function get(MessageId $messageId): ?Message
    {
        return $this->getAggregateRoot($messageId->toString());
    }
}
