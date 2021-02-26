<?php
$server = new Swoole\WebSocket\Server("0.0.0.0", 9501);

$server->on('handshake', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    // print_r( $request->header );
    // if (如果不满足我某些自定义的需求条件，那么返回end输出，返回false，握手失败) {
    //    $response->end();
    //     return false;
    // }

    // websocket握手连接算法验证


    var_dump($request);

    echo "handshake";
    $secWebSocketKey = $request->header['sec-websocket-key'];
    $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
    if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
        $response->end();
        return false;
    }
    echo $request->header['sec-websocket-key'];
    $key = base64_encode(
        sha1(
            $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
            true
        )
    );

    $headers = [
        'Upgrade' => 'websocket',
        'Connection' => 'Upgrade',
        'Sec-WebSocket-Accept' => $key,
        'Sec-WebSocket-Version' => '13',
    ];

    // WebSocket connection to 'ws://127.0.0.1:9502/'
    // failed: Error during WebSocket handshake:
    // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
    if (isset($request->header['sec-websocket-protocol'])) {
        $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
    }

    foreach ($headers as $key => $val) {
        $response->header($key, $val);
    }


    $response->status(101);
    $response->end();
    return true;
});

$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
    var_dump($request);
    echo "open";
});

$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});


$server->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    $uri = $request->server["request_uri"];
    // filter $uri
    //

    // echo $uri;
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
            websocket = new WebSocket("ws://localhost:9501?token=aaaaadfasfs");
            // var websocket = new WebSocket('ws://10.255.0.134:9501');
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
                method:"action",
                type:"request",
                data:{
                    "id":"3",
                    "group_id" : 27,
                    "message"  : document.getElementById('text').value,
                    "url":"/index/send_all/",
                }
            } 
            websocket.send(JSON.stringify(message));
        }
        </script>

        </html>
HTML;
    $response->end($res);

});
$server->start();