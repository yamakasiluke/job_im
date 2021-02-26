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

namespace Prooph\ProophessorDo\Model\Message\Service;

use Prooph\ProophessorDo\Model\Message\EmailAddress;
use Prooph\ProophessorDo\Model\Message\UserId;

interface ChecksUniqueUsersEmailAddress
{
    public function __invoke(EmailAddress $emailAddress): ?UserId;
}
