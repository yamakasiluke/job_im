<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MultiUserTest extends TestCase
{

//    use RefreshDatabase;
    /**
     * @return void
     */
    public function test_http_register_users()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->postJson('/api/commands/register-user',
            [
                'email' => 'Sally@gmail.com',
                'password' => 'Sally@gmail.com',
            ]);
        $response->assertStatus(202);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->postJson('/api/commands/register-user',
            [
                'email' => 'Sally@gmail1.com',
                'password' => 'Sally@gmail1.com',
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
                'email' => 'Sally@gmail.com',
                'password' => 'Sally@gmail.com',
                'device_name' => 'test',
            ]);
        $response->assertStatus(202);
        echo "\nwe got token for sally: ".$response->getContent()."\n";
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->postJson('/api/commands/apply-access-token',
            [
                'email' => 'Sally@gmail1.com',
                'password' => 'Sally@gmail1.com',
                'device_name' => 'test',
            ]);
        $response->assertStatus(202);
        echo "\nwe got token for sally1: ".$response->getContent()."\n";
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
                'email' => 'Sally@gmail1.com',
                'password' => 'Sally@gmail1.com',
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
