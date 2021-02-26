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

namespace Prooph\ProophessorDo\Model\Todo;

use Prooph\ProophessorDo\Model\Enum;

/**
 * @method static TodoReminderStatus OPEN()
 * @method static TodoReminderStatus CLOSED()
 */
final class TodoReminderStatus extends Enum
{
    public const OPEN = 'open';
    public const CLOSED = 'closed';
}
