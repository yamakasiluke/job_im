<?php
    error_reporting(0);
    require __DIR__ . '/../include/bootstrap.php';
    $pm = new SwooleTest\ProcessManager;
    $pm->setWaitTimeout(5);
    $s = microtime(true);

    define('LARAVEL_START', microtime(true));
    require __DIR__.'/../../../vendor/autoload.php';
    $app = require_once __DIR__.'/../../../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$server = new Swoole\WebSocket\Server("0.0.0.0", 9502);
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

$pm->parentFunc = function () use ($pm, $s) {
error_reporting(0);
    $client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC); //同步阻塞
    $ret = $client->connect('127.0.0.1', 9501, 0.5);
    $cli = new SwooleTest\Samtleben\WebsocketClient;
    $connected = $cli->connect('0.0.0.0', 9502, '/');
    Assert::assert($connected);
    $cli->sendRecv('shutdown');
    $pm->kill();
};
$pm->childFunc = function () use ($pm, $kernel, $server) {
    error_reporting(0);
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


//    $req = Illuminate\Http\Request::create(
//      "/api/commands/apply-access-token",
//      'POST',
//      $data,[],[],[],
//      json_encode($data)
//    );
//    $r = $kernel->handle($req);
//    $token = $r->getContent();


   $server->set([
           'log_file' => '/dev/null'
       ]);
   $server->on('open', function (Swoole\WebSocket\Server $server, $request) {

        $r = requestCommand(["fd" => $request->fd], "user-online");
        $r = json_decode($r);
        Assert::same($request->fd, $r->fd);
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
   });

   $server->on('close', function ($ser, $fd) {
       // step 3 offline
//           echo "client {$fd} closed\n";
   });
   $server->start();
};
$pm->childFirst();
$pm->run();
?>
