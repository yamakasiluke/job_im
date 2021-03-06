<?php

/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Model\UserTest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Prooph\ProophessorDo\Model\AccessToken\AccessToken;
use Prooph\ProophessorDo\Model\AccessToken\Event\ApplyAccessToken;
use Prooph\ProophessorDo\Model\AccessToken\TokenId;
use Prooph\ProophessorDo\Model\User\Event\UserWasRegistered;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\Event\RegisterUser;
use Prooph\ProophessorDo\Model\User\Event\UserLogin;
use Prooph\ProophessorDo\Model\User\Event\UserOnline;
use Prooph\ProophessorDo\Model\User\Event\UserWasRegisteredAgain;
use Prooph\ProophessorDo\Model\User\User;
use Prooph\ProophessorDo\Model\User\UserId;
use Prooph\ProophessorDo\Model\User\UserName;
use Tests\TestCase;

class UserTest extends TestCase
{

    /**
     * @test
     */
    public function it_registers_a_new_user(): User
    {
        $userId = UserId::generate();
        $name = UserName::fromString('John Doe');
        $emailAddress = EmailAddress::fromString('john.doe@example.com');
        $password = "readytohash";
        $user = User::registerWithData($userId, $name, $emailAddress, $password);

        $this->assertInstanceOf(User::class, $user);

        $events = $this->popRecordedEvent($user);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(RegisterUser::class, $events[0]);

        $expectedPayload = [
            'name' => $name->toString(),
            'email' => $emailAddress->toString(),
        ];
        $hashPassword = $events[0]->payload()['password'];
        $newPayload = $events[0]->payload();
        unset($newPayload['password']);
        $this->assertTrue(Hash::check($password, $hashPassword), 'The provided credentials are incorrect.');
        $this->assertEquals($expectedPayload, $newPayload);
        return $user;
    }


    /**
     * @test
     */
    public function it_apply_access_token(): AccessToken
    {
        $user = $this->it_registers_a_new_user();

        $tokenId = TokenId::generate();
        $tokenableId = $user->userId();
        $name = "token created in test";
        $token = hash('sha256', $plainTextToken = Str::random(40));
        $tokenableType = "App\\Models\\User";
        $abilities = ['*'];
        $accessToken = AccessToken::applyToken(
            $tokenId,
            $tokenableId,
            $name,
            $token,
            $tokenableType,
            $abilities
        );

        $this->assertInstanceOf(AccessToken::class, $accessToken);

        $events = $this->popRecordedEvent($accessToken);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(ApplyAccessToken::class, $events[0]);

        $expectedPayload = [
            'tokenable_id' => $tokenableId->toString(),
            'name' => $name,
            'token' => $token,
            'abilities' => $abilities,
            'tokenable_type' => $tokenableType,
        ];

        $this->assertEquals($expectedPayload, $events[0]->payload());

        return $accessToken;
    }


    /**
     * @test
     */
    public function it_registers_a_new_user_again(): void
    {
        $userId = UserId::generate();
        $name = UserName::fromString('John Doe');
        $emailAddress = EmailAddress::fromString('john.doe@example.com');
        $password = "readytohash";
        $events = [
            RegisterUser::withData($userId, $name, $emailAddress, $password),
        ];

        /** @var $user User */
        $user = $this->reconstituteAggregateFromHistory(User::class, $events);

        $user->registerAgain($name);

        $events = $this->popRecordedEvent($user);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(UserWasRegisteredAgain::class, $events[0]);

        $expectedPayload = [
            'name' => $name->toString(),
            'email' => $emailAddress->toString(),
        ];

        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    /**
     * @test
     */
    public function it_login_a_user(): User
    {

//        $user1 = \App\Models\User::factory()->create();
//        Sanctum::actingAs(
//            $user1,
//            ['*']
//        );

//              'user_id' => $userId,
//            'email' => $email,
//            'password' => $password,


        $user = $this->it_registers_a_new_user();
        $user->userLogin();

        $this->assertInstanceOf(User::class, $user);

        $events = $this->popRecordedEvent($user);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(UserLogin::class, $events[0]);

        $expectedPayload = [
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        return $user;
    }
    /**
     * @test
     */
    public function it_online_a_user(): User
    {
        $user = $this->it_login_a_user();
        $user->userOnline(0);
        $this->assertInstanceOf(User::class, $user);
        $events = $this->popRecordedEvent($user);

        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(UserOnline::class, $events[0]);

        $expectedPayload = [
            'fd' => 0
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        return $user;
    }

}

