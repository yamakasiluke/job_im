<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/sanctum/token', function (Request $request) {
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
//    return $user->tokens()->getResults();

    return $user->createToken($user->id, $request->device_name)->plainTextToken;
});
Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return "adsfa";
});

Route::post('/commands/try', [
    function () {
        return "this is funasdfasd!";
    }
]);
Route::middleware(['command_name'])->group(function () {
    Route::post('/commands/apply-access-token', [
        'as' => 'command::apply-access-token',
        'uses' => 'ApiCommandController@postAction'
    ]);
    Route::post('/commands/send-message-to-group', [
        'as' => 'command::send-message-to-group',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/send-message-to-group-member', [
        'as' => 'command::send-message-to-group-member',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/create-group', [
        'as' => 'command::apply-access-token',
        'uses' => 'ApiCommandController@postAction'
    ]);

    Route::post('/commands/user-online', [
        'as' => 'command::apply-access-token',
        'uses' => 'ApiCommandController@postAction'
    ]);






    Route::post('/commands/register-user', [
        'as' => 'command::register-user',
        'uses' => 'ApiCommandController@postAction'
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
//
//    'register-user' => \
//    'user-login' => \P
//  'user-logout' => \
//    'user-online' => \
//      'apply-access-token

//  'enter-group' => \
//  'exit-group' => \P

//  'send-message' =>

//    'user-offline' =>
//  'user-anonymous' =


//    SendMessageToGroupMember


//    Route::post('/commands/send-message', [
//        'as' => 'command::publish-good',
//        'uses' => 'ApiCommandController@postAction'
//    ]);
//
//    Route::post('/commands/enter-group', [
//        'as' => 'command::publish-good',
//        'uses' => 'ApiCommandController@postAction'
//    ]);
});
