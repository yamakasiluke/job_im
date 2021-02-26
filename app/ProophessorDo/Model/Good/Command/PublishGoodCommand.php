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

namespace Prooph\ProophessorDo\Model\Good\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ProophessorDo\Model\Good\GoodId;

final class PublishGoodCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function with(string $goodId): PublishGoodCommand
    {
        return new self([
            'good_id' => $goodId,
        ]);
    }

    public function goodId(): GoodId
    {
        return GoodId::fromString($this->payload['good_id']);
    }

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'good_id');
        Assertion::uuid($payload['good_id']);

        $this->payload = $payload;
    }
}
