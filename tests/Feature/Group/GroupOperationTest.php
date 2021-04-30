<?php

namespace Tests\Feature\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Tests\TestCase;

class GroupOperationTest extends TestCase
{

    private $userId1 = '0d4246fd-ab08-4557-b8d2-4db1ad8f6168', $userId2= '92445168-8427-4851-9a6c-6506444f190a';

//    use RefreshDatabase;
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
        echo "\nwe got userid from sally login: ".$response->getContent()."\n";
        $response->assertStatus(202);
        $this->userId1 = json_decode($response->getContent())->user_id;
        return json_decode($response->getContent())->user_id;
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
        echo "\nwe got token for sally: ".$response->getContent()."\n";
        $response->assertStatus(202);

        return json_decode($response->getContent())->token;
    }
    /**
     * @return void
     */
    public function test_http_user_login1(): string
    {

        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$this->test_http_apply_access_token1(),

        ])->postJson('/api/commands/user-login',
            [
                'email' => 'Sally@gmail1.com',
                'password' => 'Sally@gmail1.com',
            ]);
        echo "\nwe got userid from sally1 login: ".$response->getContent()."\n";
        $response->assertStatus(202);
        $this->userId2 = json_decode($response->getContent())->user_id;
        return json_decode($response->getContent())->user_id;
    }
    /**
     * @return string
     */
    public function test_http_apply_access_token1(): string
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->postJson('/api/commands/apply-access-token',
            [
                'email' => 'Sally@gmail1.com',
                'password' => 'Sally@gmail1.com',
                'device_name' => 'test',
            ]);
        echo "\nwe got token for sally1: ".$response->getContent()."\n";
        $response->assertStatus(202);

        return json_decode($response->getContent())->token;
    }
    /**
     * @return string
     */
    public function test_http_create_group(): string
    {
        $token = $this->test_http_apply_access_token();
        $userId = $this->test_http_user_login();
        [$id, $token] = explode('|', $token, 2);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$token,

        ])->postJson('/api/commands/create-group',
            [
                'members' => [$userId],
            ]);
        echo "\nwe got groupid for sally created group: ".$response->getContent()."\n";
        $response->assertStatus(202);
        return  json_decode($response->getContent())->group_id;
    }



    /**
     * @return string
     */
    public function test_http_enter_group(): string
    {
        $groupId = $this->test_http_create_group();
        $token = $this->test_http_apply_access_token();
        [$id, $token] = explode('|', $token, 2);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$token,

        ])->postJson('/api/commands/enter-group',
            [
                'group_id' => $groupId,
                'members' => [$this->userId1, $this->userId2],
            ]);
        echo "\nwe got groupid for sally created group: ".$response->getContent()."\n";
        $response->assertStatus(202);
        return $response->getContent();
    }

}
