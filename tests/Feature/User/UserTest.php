<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public $email = 'Sally@gmail3.com';
    public $password = 'Sally@gmail3.com';

//    use RefreshDatabase;
    /**
     * @return void
     */
    public function test_http_register_user()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->postJson('/api/commands/register-user',
            [
                'email' => $this->email,
                'password' => $this->password,
            ]);
        $response->assertStatus(202);
    }
    /**
     * @return string
     */
    public function test_http_apply_access_token(): string
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->postJson('/api/commands/apply-access-token',
            [
                'email' => $this->email,
                'password' => $this->password,
                'device_name' => 'test',
            ]);
        $response->assertStatus(202);
        return json_decode($response->getContent())->token;
    }
    /**
     * @return void
     */
    public function test_http_user_login(): string
    {

        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$this->test_http_apply_access_token(),

        ])->postJson('/api/commands/user-login',
            [
                'email' => $this->email,
                'password' => $this->password,
            ]);
        $response->assertStatus(202);
        return $response->getContent();
    }
    /**
     * @return void
     */
    public function test_http_user_online()
    {

        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$this->test_http_apply_access_token(),

        ])->postJson('/api/commands/user-online',
            [
                'fd' => 0,
            ]);
        $response->assertStatus(202);
    }
}
