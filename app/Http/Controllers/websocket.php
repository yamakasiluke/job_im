<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class websocket extends BaseController
{
    public function get(Request $request ): string
    {
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
        <br/>token<select id="token">
            <option value="6ae196d0-2395-40e4-aae6-53bf44ec3fa2|IQrzg8BPCPL7ZTbA2nv8Qa4hInoMD3AfEKBv7Pf2">1</option>
            <option value="a5ef291a-d9aa-4413-9b6b-bf54883a4957|c68Ql8WJ8N3bYMIOdqv18mYPMezAq0RNjmbCQ4dW">2</option>
        </select>
        <button onclick="send()">发送消息</button>
        <button onclick="token()">SETTOKEN</button>
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
        var s ;
        function token() {
            if ('WebSocket' in window) {
                s = document.getElementById('token').value;
            try {
                connect(s)
            } catch (e) {
                setMessageInnerHTML("WebSocket连接发生错误");
            }

            }
            else {
                alert('当前浏览器 Not support websocket')
            }
        }



        function connect(s) {
            websocket = new WebSocket("ws://localhost:9502?token="+s);
            // var websocket = new WebSocket('ws://localhost:9501');
            websocket.onopen = function() {
                setMessageInnerHTML("WebSocket连接成功");
                // var message = {
                //     method:"verify",
                //     type:"request",
                //     data:{
                //         "id":"3",
                //         "username":"aaaaaa",
                //         "access_token":"U6cACI_MrGykpXpNyhq5ME3NcAQfu8w4",
                //     }
                // }
                // websocket.send(JSON.stringify(message));
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
                    connect(s);
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
                params:message,
                
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
                event:"sendMessageToGroup",
                type:"request",
                data:{
                    "user_id":"46efd0c7-8b56-41c9-85dc-76cbec25562c",
                    "id":"3",
                    'message_text' : document.getElementById('text').value,
                    'sender_id' : '0d4246fd-ab08-4557-b8d2-4db1ad8f6168',
                    'receiver_id' : '5bd57bd3-cdbe-4c77-9de4-7b80df7e1e2f',
                }
//                
            }
            // var message = {"group_id":"3ebc67f4-22a1-4a63-a24c-fc8dc2eab227","message_text":"this is start", "message_id":"4a9b1099-9da2-4f7e-8110-99cf83ff330f"}
            websocket.send(JSON.stringify(message));
        }
        </script>

        </html>
HTML;

        return $res;
    }
}
