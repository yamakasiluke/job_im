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
use Prooph\ProophessorDo\Model\AccessToken\AccessToken;
use Prooph\ProophessorDo\Model\AccessToken\TokenId;
use Prooph\ProophessorDo\Model\AccessToken\TokenList;

final class EventStoreTokenList extends AggregateRepository implements TokenList
{
    public function save(AccessToken $accessToken): void
    {
        $this->saveAggregateRoot($accessToken);
    }

    public function get(TokenId $tokenId): ?AccessToken
    {
        return $this->getAggregateRoot($tokenId->toString());
    }
}
