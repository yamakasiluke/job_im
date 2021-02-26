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

namespace Prooph\ProophessorDo\Model\AccessToken\Handler;

use Prooph\ProophessorDo\Model\AccessToken\AccessToken;
use Prooph\ProophessorDo\Model\AccessToken\Command\ApplyAccessTokenCommand ;
use Prooph\ProophessorDo\Model\AccessToken\TokenId;
use Prooph\ProophessorDo\Model\AccessToken\TokenList;
use Prooph\ProophessorDo\Model\User\Exception\UserAlreadyExists;
use Prooph\ProophessorDo\Model\User\Exception\UserNotFound;
use Prooph\ProophessorDo\Model\User\Service\ChecksUniqueUsersEmailAddress;
use Prooph\ProophessorDo\Model\User\User;
use Prooph\ProophessorDo\Model\User\UserCollection;
use TheSeer\Tokenizer\TokenCollection;

class ApplyAccessTokenHandler
{
    /**
     * @var TokenList
     */
    private $tokenList;

    public function __construct(
        TokenList $tokenList
    ) {
        $this->tokenList = $tokenList;
    }

    public function __invoke(ApplyAccessTokenCommand $command): void
    {
        $user = AccessToken::applyToken($command->userId(), TokenId::generate());

        $this->tokenList->save($user);

    }
}
