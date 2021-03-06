<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\Receiver;
use Prooph\ProophessorDo\Model\Message\Sender;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['command_name'])->group(function () {
    Route::post('/commands/register-user', [
        'as' => 'command::register-user',
        function (Request $request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $json = $request->json();
            $userId = \Prooph\ProophessorDo\Model\User\UserId::generate()->toString();
            $json->add([
                'user_id' => $userId,
            ]);
            $request = $request->setJson($json);
            $response = App::call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
            return $response;
        }
    ]);
    Route::post('/commands/apply-access-token', [
        'as' => 'command::apply-access-token',
        function (Request $request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $json = $request->json();
            $tokenId = \Prooph\ProophessorDo\Model\AccessToken\TokenId::generate()->toString();
            $token = $user->createToken($tokenId, $request->device_name)->plainTextToken;
            $json->add([
                'token_id' => $tokenId,
                'tokenable_id' => $user->id,
                'tokenable_type' => "App\\Models\\User",
                'name' => $request->device_name,
                'token' => $token,
                'abilities' => ['*'],
            ]);
            $request = $request->setJson($json);
            $response = App::call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
            if($response->isSuccessful()){
                $json = json_decode($response->getContent());
                $json->token = $token;
                $response->setJson(json_encode($json));
            }
            return $response;
        }
    ]);
});

Route::middleware(['command_name', 'auth:sanctum'])->group(function () {
    Route::post('/commands/user-login', [
        'as' => 'command::user-login',
        function (Request $request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $json = $request->json();
            $json->add([
                'user_id' => $request->user()->id,
            ]);
            $request = $request->setJson($json);
            if (! Hash::check($request->password, Auth::user()->getAuthPassword())) {
                throw ValidationException::withMessages([
                    'password' => ['The provided credentials are incorrect.'],
                ]);
            }

            $response = App::call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
            if($response->isSuccessful()){
                $json = json_decode($response->getContent());
                $json->user_id = $request->user()->id;
                $response->setJson(json_encode($json));
            }
            return $response;
        }
    ]);
    Route::post('/commands/user-logout', [
        'as' => 'command::user-logout',
        'uses' => 'ApiCommandController@postAction'
    ]);
    Route::post('/commands/user-anonymous', [
        'as' => 'command::user-anonymous',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/send-message-to-group', [
        'as' => 'command::send-message-to-group',
        function (Request $request) {
            $json = $request->json();
            $messageId = MessageId::generate()->toString();
            $json->add([
                'message_id' => $messageId,
                'sender' => Sender::USER()->getValue(),
                'receiver' => Receiver::GROUP()->getValue(),
            ]);
            $request = $request->setJson($json);
            $response = App::call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
            if($response->isSuccessful()){
                $json = json_decode($response->getContent());
                $json->message_id = $messageId;
                $response->setJson(json_encode($json));
            }
            return $response;
        }
    ]);

//command bus
    Route::post('/commands/send-message-to-group-member', [
        'as' => 'command::send-message-to-group-member',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/create-group', [
        'as' => 'command::create-group',
        function (Request $request) {
            $groupId = GroupId::generate()->toString();
            $json = $request->json();
            $json->add([
                'group_id' => $groupId,
                'owner' => $request->user()->id,
            ]);
            $request = $request->setJson($json);
            $response = App::call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
            if($response->isSuccessful()){
                $json = json_decode($response->getContent());
                $json->group_id = $groupId;
                $response->setJson(json_encode($json));
            }
            return $response;

        }
    ]);
    Route::post('/commands/enter-group', [
        'as' => 'command::enter-group',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/user-online', [
        'as' => 'command::user-online',
        function (Request $request) {
            $json = $request->json();
            $json->add([
                'user_id' => $request->user()->id,
            ]);
            $request = $request->setJson($json);
            $response = App::call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
            if($response->isSuccessful()){
                $json = json_decode($response->getContent());
                $json->fd = $request->json("fd");
                $response->setJson(json_encode($json));
            }
            return $response;
        }
    ]);

    Route::post('/commands/add-deadline-to-todo', [
        'as' => 'command::add-deadline-to-todo',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/add-reminder-to-todo', [
        'as' => 'command::add-reminder-to-todo',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/mark-todo-as-done', [
        'as' => 'command::mark-todo-as-done',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/mark-todo-as-expired', [
        'as' => 'command::mark-todo-as-expired',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/reopen-todo', [
        'as' => 'command::reopen-todo',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/post-todo', [
        'as' => 'command::post-todo',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/publish-good', [
        'as' => 'command::publish-good',
        'uses' => 'ApiCommandController@postAction'
    ]);
});
