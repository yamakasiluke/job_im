<?php

use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/../include/bootstrap.php';
$pm = new SwooleTest\ProcessManager;
$pm->setWaitTimeout(5);
$s = microtime(true);
$pm->parentFunc = function () use ($pm) {
    $cli = new SwooleTest\Samtleben\WebsocketClient;
    $connected = $cli->connect('0.0.0.0', 9501, '/?token=2paiZiWRGN7pFABbQKSd9Pxl2z0zeH0s0MLp9k6Z');
//    Assert::assert($connected);
    echo "herer";
//    $cli->sendRecv('shutdown');
    echo shell_exec('php artisan swoole:http stop');
    $pm->kill();
//    run(function () {
//        $i = 1;
//        $cli = new Client('0.0.0.0', 9502);
//        $cli1 = new Client('0.0.0.0', 9502);
//        $ret = $cli->upgrade("/?token=2paiZiWRGN7pFABbQKSd9Pxl2z0zeH0s0MLp9k6Z");
//        $ret1 = $cli1->upgrade("/?token=YVKPFE0mc3f1gIUQBb1NsncULaYokaT70YwfYB1L");
//        if ($ret) {
//            while(true) {
//                $cli->push($i++);
//                $cli1->push($i++);
////                Coroutine::sleep(1);
//                echo "cli1 ".$cli->recv()->data."\n";
//                echo "cli2 ".$cli1->recv()->data."\n";
//                Coroutine::sleep(1);
//            }
//        }
//    });
};
$pm->childFunc = function () use ($pm) {
    echo shell_exec('php artisan swoole:http start');
//    Artisan::call('swoole:http start');
};
$pm->childFirst();
$pm->run();
?>