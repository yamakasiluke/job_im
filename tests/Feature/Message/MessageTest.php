<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{

//    use RefreshDatabase;
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
        echo "userid : ";
        $response->dump();
        $response->assertStatus(200);
        return $response->content();
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
        echo "groupid : ";
        $response->dump();
        $response->assertStatus(202);
        return $response->getContent();
    }


    /**
     * @return string
     */
    public function test_http_send_message_to_group(): string
    {
        $token = $this->test_http_apply_access_token();
        $groupId = $this->test_http_create_group();
        [$id, $token] = explode('|', $token, 2);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$token,

        ])->postJson('/api/commands/send-message-to-group',
            [
                'sender_id' => $id,
                'receiver_id' => $groupId,
                'message_text' => "this is send by test",
            ]);
        echo " meeesgeaid: ";$response->dump();

        $response->assertStatus(200);
        return $response->getContent();
    }

    /**
     * @return string
     */
    public function test_http_send_message_to_group_member()
    {
        $token = $this->test_http_apply_access_token();
        $groupId = $this->test_http_create_group();
        $messageId = $this->test_http_send_message_to_group();
        [$id, $token] = explode('|', $token, 2);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/commands/send-message-to-group-member',
            [
                'message_id' => $messageId,
                'group_id' => $groupId,
                'message_text' => "this is send by test",
            ]);
        $response->assertStatus(202);
    }

}
