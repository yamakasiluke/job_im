<?php
/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2017 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2017 Sascha-Oliver Prolic <saschaprolic@googldeviceName.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\ProophessorDo\Model\AccessToken;

use Prooph\ProophessorDo\Model\ValueObject;

final class DeviceName implements ValueObject
{
    /**
     * @var string
     */
    private $deviceName;

    public static function fromString(string $deviceName): DeviceName
    {
        return new self($deviceName);
    }

    private function __construct(string $deviceName)
    {
        $this->deviceName = $deviceName;
    }

    public function toString(): string
    {
        return $this->deviceName;
    }

    public function sameValueAs(ValueObject $other): bool
    {
        return get_class($this) === get_class($other) && $this->toString() === $other->toString();
    }
}
