<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

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
                'email' => 'Sally@gmail.com',
                'password' => 'Sally@gmail.com',
            ]);
        $response->dump();
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
                'email' => 'Sally@gmail.com',
                'password' => 'Sally@gmail.com',
                'device_name' => 'test',
            ]);
        $response->assertStatus(200);
        return $response->content();
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
                'email' => 'Sally@gmail.com',
                'password' => 'Sally@gmail.com',
            ]);
        $response->dump();
        $response->assertStatus(200);
        return $response->content();
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
        $response->dump();
        $response->assertStatus(202);
    }
}
