<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

use Illuminate\Support\Facades\App;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

//$response->send();
//
//$kernel->terminate($request, $response);

//$server->on('request', function ($request, $response) {
//    var_duhandshakemp($request);
//    global $kernel;
//    $r = $kernel->handle(
//        $request = Illuminate\Http\Request::create();
//    );
////    $response->header("Content-Type", "text/html; charset=utf-8");
//    $response->end($r->getContent());
//});
//$req = Illuminate\Http\Request::create(
//    "/api/commands/send-message-to-group-member",
//    'POST',
//    [],[],[],[],
//);
//$r = $kernel->handle($req
//);

//$data = (object)array(
//    'email' => 'Sally@gmail.com',
//    'password' => 'Sally@gmail.com',
//    'device_name' => 'test');
//var_dump(json_encode($data));
//$req = Illuminate\Http\Request::create(
//    "/api/commands/apply-access-token",
//    'POST',
//    [],[],[],[],
//    json_encode($data)
//);
//$r = $kernel->handle($req);
//var_dump($r->getContent());

$server = new Swoole\WebSocket\Server("0.0.0.0", 9502);

//$server->on('handshake', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
//    // print_r( $request->header );
//    // if (如果不满足我某些自定义的需求条件，那么返回end输出，返回false，握手失败) {
//    //    $response->end();
//    //     return false;
//    // }
//
//    // websocket握手连接算法验证
//
//
////    var_dump($request);
//
////    echo "handshake";
//    $secWebSocketKey = $request->header['sec-websocket-key'];
//    $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
//    if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
//        $response->end();
//        return false;
//    }
////    echo $request->header['sec-websocket-key'];
//    $key = base64_encode(
//        sha1(
//            $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
//            true
//        )
//    );
//
//    $headers = [
//        'Upgrade' => 'websocket',
//        'Connection' => 'Upgrade',
//        'Sec-WebSocket-Accept' => $key,
//        'Sec-WebSocket-Version' => '13',
//    ];
//
//    // WebSocket connection to 'ws://127.0.0.1:9502/'
//    // failed: Error during WebSocket handshake:
//    // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
//    if (isset($request->header['sec-websocket-protocol'])) {
//        $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
//    }
//
//    foreach ($headers as $key => $val) {
//        $response->header($key, $val);
//    }
//
//
//    $response->status(101);
//    $response->end();
//
//    // step 1 online get fd
//    return true;
//});

$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
//    var_dump($request);
    echo "open";
});

$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    global $kernel;
    $d = json_decode($frame->data);
    $d = ['email' => "Sally@gmail.com", 'password' => "2ally@gmail.com",];
//    $d->fd = $frame->fd;
//    $d->email = "Sally@gmail.com";
//    $d->password = "Sally@gmail.com";
//     step 2 send command
    $req = Illuminate\Http\Request::create(
        "/api/commands/register-user",
        'POST',
        $d,[],[],[],
        json_encode($d)
    );
    $r = $kernel->handle($req
    );
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "Asdfadsfa".$r->getContent());
});

$server->on('close', function ($ser, $fd) {
    // step 3 offline
    echo "client {$fd} closed\n";
});


$server->on('request', function (swoole_http_request $request, swoole_http_response $response) {
//    global $kernel;
//    $d = json_decode($frame->data);
//    $d->fd = $frame->fd;
//     step 2 send command
//    $req = Illuminate\Http\Request::create(
//        "/api/commands/register-user",
//        'POST',
//        [],[],[],[],
////        json_encode($d)
//    );
//    $r = $kernel->handle($req
//    );


//    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//    $server->push($frame->fd, $r->getContent());
//    $response->end($r->getContent());
//    global $kernel;
//    $req = Illuminate\Http\Request::create(
//        "/api/commands/send-message-to-group-member",
//        'POST'
//    );
//    $r = $kernel->handle($req
//    );
//    global $app;
//    $app->call('App\Http\Controllers\ApiCommandController@postAction', [$request]);
//    var_dump($app->call('App\Http\Controllers\ApiCommandController@postAction', [$request]));
    //    echo "herer1";
//    global $kernel,$app;
//    $a = $app->make(Prooph\ServiceBus\CommandBus::class);
//    var_dump($a);
//    $req = Illuminate\Http\Request::capture();
//    $req = Illuminate\Http\Request::create(
//        "/api/commands/user-online",
//        'POST',
//        [],[],[],[],
//        '{"user_id":"46efd0c7-8b56-41c9-85dc-76cbec25562c"}'
//    );
//    $req = Illuminate\Http\Request::create("/api/sanctum/token", 'POST');

//    $r = $kernel->handle($req
//    );
//    $response->end($r->getContent());


//    $uri = $request->server["request_uri"];
//    // filter $uri
//    //
//
//    // echo $uri;
    $res = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        </head>
        <body>
        Welcome<br/><input id="text" type="text"/>
        <button onclick="send()">发送消息</button>
        <button onclick="get_all_user()">getAllUser</button>
        <button onclick="invite_friend()">invite_friend</button>
        <button onclick="save_relation()">save_relation</button>
        <hr/>
        <button onclick="closeWebSocket()">关闭WebSocket连接</button>
        <hr/>
        <div id="message"></div>
        </body>
        <script>
        var websocket = null;
        //判断当前浏览器是否支持WebSocket
        if ('WebSocket' in window) {
            try {
                connect()
            } catch (e) {
                setMessageInnerHTML("WebSocket连接发生错误");
            }

        }
        else {
            alert('当前浏览器 Not support websocket')
        }




        function connect() {
            websocket = new WebSocket("ws://localhost:9502?token=aaaaadfasfs");
            // var websocket = new WebSocket('ws://localhost:9502');
            websocket.onopen = function() {
                setMessageInnerHTML("WebSocket连接成功");
                var message = {
                    method:"verify",
                    type:"request",
                    data:{
                        "id":"3",
                        "username":"aaaaaa",
                        "access_token":"U6cACI_MrGykpXpNyhq5ME3NcAQfu8w4",
                    }
                }
                websocket.send(JSON.stringify(message));
            };

            websocket.onmessage = function(e) {

                console.log('Message:', e.data);
                setMessageInnerHTML(event.data);
            };

            websocket.onclose = function(e) {
                console.log('Socket is closed. Reconnect will be attempted in 1 second.', e.reason);
                setMessageInnerHTML("WebSocket连接关闭");
                setTimeout(function() {
                    setMessageInnerHTML("重新连接");
                    connect();
                }, 1000);
            };

            websocket.onerror = function(err) {
                console.error('Socket encountered error. Closing socket');
                setMessageInnerHTML("WebSocket连接发生错误");
                websocket.close();
            };
        }

        // //连接发生错误的回调方法
        // websocket.onerror = function (e) {

        // setMessageInnerHTML("WebSocket连接发生错误");
        // };

        //连接成功建立的回调方法
        // websocket.onopen = function (e) {
        // setMessageInnerHTML("WebSocket连接成功");
        // }

        //接收到消息的回调方法
        // websocket.onmessage = function (event) {
        //     setMessageInnerHTML(event.data);

        //     // controller/view
        //     // chatlist/index
        //     // messagelsit/indexx

        //     // update users
        //     // update message
        // }

        //连接关闭的回调方法
        // websocket.onclose = function () {
        // setMessageInnerHTML("WebSocket连接关闭");
        // }

        //监听窗口关闭事件，当窗口关闭时，主动去关闭websocket连接，防止连接还没断开就关闭窗口，server端会抛异常。
        window.onbeforeunload = function () {
            closeWebSocket();
        }

        //将消息显示在网页上
        function setMessageInnerHTML(innerHTML) {
            document.getElementById('message').innerHTML += innerHTML + '<br/>';
        }

        //关闭WebSocket连接
        function closeWebSocket() {
            websocket.close();
        }

        //发送消息
        function create_room() {
            var message = document.getElementById('text').value;
            message = {
                url:"/index/create_room/",
                params:message
            }
            websocket.send(JSON.stringify(message));
        }
        //发送消息
        function invite_friend() {
            message = {
                url:"/index/invite_friend/",
                data:{
                    "id":"3",
                    "friend_id" : 5,
                    "message"  : ""
                }
            }
            websocket.send(JSON.stringify(message));
        }
        function save_relation() {
            message = {
                url:"/index/save_relation/",
                data:{
                    "id":"3",
                    "friend_id" : 5,
                    "message"  : ""
                }
            }
            websocket.send(JSON.stringify(message));
        }
        function chat_list() {
            var message = document.getElementById('text').value;
            message = {
                url:"/index/chat_list/",
                params:message
            }
            websocket.send(JSON.stringify(message));
        }
        function get_all_user() {
            var message = document.getElementById('text').value;
            message = {
                url:"/index/get_all_user/",
                params:message
            }
            websocket.send(JSON.stringify(message));
        }
        function send() {

            var message = document.getElementById('text').value;
            var message = {
                "user_id":"46efd0c7-8b56-41c9-85dc-76cbec25562c",
                method:"action",
                type:"request",
                data:{
                    "user_id":"46efd0c7-8b56-41c9-85dc-76cbec25562c",
                    "id":"3",
                    "group_id" : 27,
                    "message"  : document.getElementById('text').value,
                    "url":"/index/send_all/",
                }
            }
            var message = {"group_id":"3ebc67f4-22a1-4a63-a24c-fc8dc2eab227","message_text":"this is start", "message_id":"4a9b1099-9da2-4f7e-8110-99cf83ff330f"}
            websocket.send(JSON.stringify(message));
        }
        </script>

        </html>
HTML;
    $response->end($res);
//    $response->end();

});
$server->start();
