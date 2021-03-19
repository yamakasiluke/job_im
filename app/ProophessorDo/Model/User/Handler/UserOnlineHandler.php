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

namespace Prooph\ProophessorDo\Model\User\Handler;

use Illuminate\Support\Facades\App;
use Prooph\ProophessorDo\Model\User\Command\UserOnlineCommand;
use Prooph\ProophessorDo\Model\User\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\User\Exception\UserNotUseIm;
use Prooph\ProophessorDo\Model\User\UserCollection;
use SwooleTW\Http\Websocket\Facades\Websocket;


class UserOnlineHandler
{
    /**
     * @var int
     */
    private $fd;
    /**
     * @var UserCollection
     */
    private $userCollection;

    public function __construct(
        UserCollection $userCollection
    ) {
        $this->userCollection = $userCollection;
    }

    public function __invoke(UserOnlineCommand $command): void
    {
//        if (!App::environment('testing')) {
//            Websocket::;
//            if(!isset($server))
//                throw UserNotUseIm::withUserId($command->userId());
//        }
        if (! $user = $this->userCollection->get($command->userId())) {
            throw UserNotFound::withUserId($command->userId());
        } else {
            $user->userOnline($command->fd());
        }

        $this->userCollection->save($user);
    }
}
