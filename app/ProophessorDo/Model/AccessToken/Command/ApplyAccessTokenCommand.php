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

namespace Prooph\ProophessorDo\Model\AccessToken\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ProophessorDo\Model\AccessToken\TokenId;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\User\UserName;
use Prooph\ProophessorDo\Model\User\DeviceName;
use Zend\Validator\EmailAddress as EmailAddressValidator;

final class ApplyAccessTokenCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;


//$request->validate([
//'email' => 'required|email',
//'password' => 'required',
//'device_name' => 'required',
//]);


    // username
    // password
//
//$request->validate([
//'email' => 'required|email',
//'password' => 'required',
//'device_name' => 'required',
//]);
    public static function withData(
        string $tokenId,
        string $tokenableId,
        string $name,
        string $token,
        string $tokenableType = "App\\Models\\User",
        array $abilities = ['*']): ApplyAccessTokenCommand
    {
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'required',
//            'device_name' => 'required',
//        ]);
//        UserId $tokenableId,
//        TokenId $tokenId,
//        $tokenableType,
//        $name,
//        $token,
//        $abilities): ApplyAccessToken
        return new self([
            'token_id' => $tokenId,
            'tokenable_id' => $tokenableId,
            'tokenable_type' => $tokenableType,
            'name' => $name,
            'token' => $token,
            'abilities' => $abilities,
        ]);
    }
    public function tokenId(): TokenId
    {
        if(isset($this->payload['token_id']))
            return TokenId::fromString($this->payload['token_id']);
        else
            return TokenId::generate();
    }
    public function tokenableId(): UserId
    {
        return UserId::fromString($this->payload['tokenable_id']);
    }
    public function tokenableType(): string
    {
        if(isset($this->payload['tokenable_type']))
            return $this->payload['tokenable_type'];
        else
            return "App\\Models\\User";
    }
    public function name(): string
    {
        return $this->payload['name'];
    }
    public function token(): string
    {
        return $this->payload['token'];
    }
    public function abilities(): array
    {
        if (isset($this->payload['abilities']))
            return $this->payload['abilities'];
        else
            return ['*'];
    }




    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'tokenable_id');
        Assertion::uuid($payload['tokenable_id']);
        Assertion::keyExists($payload, 'token');
        Assertion::string($payload['token']);
//        Assertion::keyExists($payload, 'email');
//        $validator = new EmailAddressValidator();
//        Assertion::true($validator->isValid($payload['email']));

        $this->payload = $payload;
    }
}
