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

namespace Prooph\ProophessorDo\Model\Price\Command;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\User\UserName;
use Prooph\ProophessorDo\Model\User\DeviceName;
use Zend\Validator\EmailAddress as EmailAddressValidator;

final class ApplyAccessTokenCommand extends Command implements PayloadConstructable
{
    use PayloadTrait;
    // username
    // password
//
//$request->validate([
//'email' => 'required|email',
//'password' => 'required',
//'device_name' => 'required',
//]);
    public static function withData(string $userId, string $name, string $email): RegisterUser
    {
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'required',
//            'device_name' => 'required',
//        ]);

        return new self([
            'user_id' => $userId,
            'name' => $name,
            'email' => $email,
        ]);
    }
    public function deviceName(): DeviceName
    {
        return DeviceName::fromString($this->payload['device_name']);
    }


    public function name(): UserName
    {
        return UserName::fromString($this->payload['name']);
    }

    public function emailAddress(): EmailAddress
    {
        return EmailAddress::fromString($this->payload['email']);
    }
    // username
    // password
    // anoymouse

    protected function setPayload(array $payload): void
    {
        Assertion::keyExists($payload, 'user_id');
        Assertion::uuid($payload['user_id']);
        Assertion::keyExists($payload, 'name');
        Assertion::string($payload['name']);
        Assertion::keyExists($payload, 'email');
        $validator = new EmailAddressValidator();
        Assertion::true($validator->isValid($payload['email']));

        $this->payload = $payload;
    }
}
