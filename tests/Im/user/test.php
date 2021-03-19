<?php

use App\CustomDatabaseManager;
use Illuminate\Database\DatabaseManager;
use Swoole\Coroutine;
use Swoole\Coroutine\Http\Client;
use function Swoole\Coroutine\run;
//    error_reporting(0);
    require __DIR__ . '/../include/bootstrap.php';
    $pm = new SwooleTest\ProcessManager;
    $pm->setWaitTimeout(5);
    $s = microtime(true);

    define('LARAVEL_START', microtime(true));
    require __DIR__.'/../../../vendor/autoload.php';
    $app = require_once __DIR__.'/../../../bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$server = new Swoole\WebSocket\Server("0.0.0.0", 9502);
//$server->on('workerstart', function($server, $id) {
//    $redis = new Redis();
//    $redis->connect('127.0.0.1', 6379);
//    $server->redis = $redis;
//});
    function requestCommand($data,$url,$token = ""): string{
        global $kernel;
        $req = Illuminate\Http\Request::create(
          "/api/commands/{$url}",
          'POST',
          $data,[],[],["HTTP_AUTHORIZATION"=>"Bearer {$token}"],
          json_encode($data)
        );
        $r = $kernel->handle($req);
        return $r->getContent();
    }
    $data = [
        'email' => 'Sally@gmail1.com',
        'password' => 'Sally@gmail1.com',
        'device_name' => 'test',
    ];
    $data1 = [
            'email' => 'Sally@gmail2.com',
            'password' => 'Sally@gmail2.com',
            'device_name' => 'test',
        ];
    requestCommand($data, "register-user");
    requestCommand($data1, "register-user");
    $token = requestCommand($data, "apply-access-token");
    $token1 = requestCommand($data1, "apply-access-token");
    $userId = requestCommand($data, "user-login", $token);
    $userId1 = requestCommand($data1, "user-login", $token1);
$pm->parentFunc = function () use ($pm, $s,$token,$token1) {
//error_reporting(0);

    run(function () {
        $i = 1;

        global $token,$token1;
        var_dump($token, $token1);
        $cli = new Client('0.0.0.0', 9502);
        $cli1 = new Client('0.0.0.0', 9502);
        $ret = $cli->upgrade("/?token=1");
//        $ret = $cli->upgrade("/?token={$token}");
//        $ret1 = $cli1->upgrade("/?token={$token1}");
        $ret1 = $cli1->upgrade("/?token=2");
        if ($ret) {
            while(true) {
                $cli->push($i++);
                $cli1->push($i++);
//                Coroutine::sleep(1);
                echo "cli1 ".$cli->recv()->data."\n";
                echo "cli2 ".$cli1->recv()->data."\n";
                Coroutine::sleep(1);
            }
        }
    });
//    $cli = new SwooleTest\Samtleben\WebsocketClient;
//    $connected = $cli->connect('0.0.0.0', 9502, '/');
//    Assert::assert($connected);
//    $cli->sendRecv('shutdown');
//    $pm->kill();
};
$pm->childFunc = function () use ($pm, $kernel, $server, $app) {
//    error_reporting(0);



//    $req = Illuminate\Http\Request::create(
//      "/api/commands/apply-access-token",
//      'POST',
//      $data,[],[],[],
//      json_encode($data)
//    );
//    $r = $kernel->handle($req);
//    $token = $r->getContent();

    $server->on('workerstart', function($server, $id) {
        global $app;
//        $app->bind('db'.$id, function ($app, $id) {
//        });
        $app->instance("workerid", $id);
        $app->bind('db', function ($app) {
            return new CustomDatabaseManager($app, $app['db.factory']);
        });
        $server->ttt = $id;
    });

   $server->set([
           'log_file' => '/dev/null'
       ]);
   $server->on('open', function (Swoole\WebSocket\Server $server, $request) {
       global $app;
       $app->instance("ttt", $request->fd);
//       var_dump("open ".$app->make("ttt")." ".$request->fd);
//        var_dump($request->get['token']);
       var_dump("workerid".$app->get('db')->wid());
//       var_dump($server->ttt);
        $r = requestCommand(["fd" => $request->fd], "user-online",$request->get['token']);
        var_dump($r);
//        $r = json_decode($r);
//        Assert::same($request->fd, $r->fd);
//        global $kernel;
//        $data = [
//            'user_id' => $userId,
//            'fd' => $fd,
//        ];
//        $req = Illuminate\Http\Request::create(
//          "/api/commands/send-message-to-group-member",
//          'POST',
//          $data,[],[],[],
//          json_encode($data)
//        );
//        $r = $kernel->handle($req);
   });
   $server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
       global $kernel;
       global $app;
       var_dump("open ".$app->make("ttt")." ".$frame->fd);

       global $server;//调用外部的server
       // $server->connections 遍历所有websocket连接用户的fd，给所有用户推送
       foreach ($server->connections as $fd) {
           // 需要先判断是否是正确的websocket连接，否则有可能会push失败
           if ($server->isEstablished($fd)) {
               $server->push($fd, $frame->data);
           }
       }
   //    $d = json_decode($frame->data);
   //    $d->fd = $frame->fd;
       // step 2 send command
//       $req = Illuminate\Http\Request::create(
//           "/api/commands/send-message-to-group-member",
//           'POST',
//           [],[],[],[],
//   //        json_encode($d)
//       );
//       $r = $kernel->handle($req
//       );
////           echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//       $server->push($frame->fd, $r->getContent());

       $server->push($frame->fd, $frame->data);
   });

   $server->on('close', function ($ser, $fd) {
       // step 3 offline
//           echo "client {$fd} closed\n";
   });
   $server->start();
};
$pm->childFirst();
$pm->run();
