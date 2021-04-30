<?php


use App\Http\Controllers\Controller;
use App\Http\Controllers\WebsocketCommandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\Receiver;
use Prooph\ProophessorDo\Model\Message\Sender;
use SwooleTW\Http\Server\Facades\Server;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on('connect', function ($websocket, Request $request) {
    echo "here";
    $req = Illuminate\Http\Request::create(
        "/api/commands/user-online",
        'POST'
    );
    var_dump($request->user()->id);
    $req->json()->add([
        'user_id' => $request->user()->id,
        'fd' => $request->fd,
    ]);
    $req->attributes->add(['prooph_command_name' => config(sprintf('app.command_alias.%s', basename($req->url())))]);
    $response = App::call('App\Http\Controllers\ApiCommandController@postAction', ['request' => $req]);

    if(!$response->isSuccessful()){
        Websocket::emit('message', $response->getContent());
        Websocket::close();
    }
    else
        Websocket::emit('message', ['userid'=>$request->user()->id, 'connect'=>true]);
    var_dump($request->user()->id);
});

//Websocket::on('enterGroup', function ($websocket, $data) {
//    $req = Illuminate\Http\Request::create(
//        "/api/commands/enter-group",
//        'POST',
//    );
//    $req->json()->add($data);
//    $req->attributes->add(['prooph_command_name' => config(sprintf('app.command_alias.%s', basename($req->url())))]);
//    $response = App::call('App\Http\Controllers\ApiCommandController@postAction', ['request' => $req]);
//    if(!$response->isSuccessful()){
//        Websocket::emit('message', $response->getContent());
//        Websocket::close();
//    }
//    else
//        Websocket::emit('message', []);
//});

Websocket::on('sendMessageToGroup', function ($websocket, $data) {
    echo"here";
    $req = Illuminate\Http\Request::create(
        "/api/commands/send-message-to-group",
        'POST',
    );
    $messageId = MessageId::generate()->toString();
    $req->json()->add([
        'message_id' => $messageId,
        'sender' => Sender::USER()->getValue(),
        'receiver' => Receiver::GROUP()->getValue(),
    ]);
    $req->json()->add($data);
    $req->attributes->add(['prooph_command_name' => config(sprintf('app.command_alias.%s', basename($req->url())))]);
    $response = App::call('App\Http\Controllers\ApiCommandController@postAction', ['request' => $req]);
//    if(!$response->isSuccessful()){
//        Websocket::emit('message', $response->getContent());
//    }
//    else
//        Websocket::broadcast()->emit('message', ['sender_id'=>$data['sender_id'], 'message'=>$data['message_text']]);

});

Websocket::on('disconnect', function ($websocket) {
    // called while socket on disconnect
});

Websocket::on('example', function ($websocket, $data) {
    echo "exampleexampleexampleexampleexample";
    $websocket->emit('message', $data);
});

Websocket::on('message', function ($websocket, $data) {
    $websocket->emit('message', $data);
});
