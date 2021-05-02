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

namespace Prooph\ProophessorDo\ProcessManager;

use Prooph\ProophessorDo\Model\Group\Command\EnterGroupCommand;
use Prooph\ProophessorDo\Model\User\Event\RegisterUser;
use Prooph\ServiceBus\CommandBus;

class RegisterUserEnterGroupProcessManager
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(RegisterUser $event): void
    {
        $this->commandBus->dispatch(EnterGroupCommand::withData(
            "5bd57bd3-cdbe-4c77-9de4-7b80df7e1e2f",
            [$event->userId()->toString()]
        ));
    }
}
