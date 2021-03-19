<?php
$cli = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$cli->on("connect", function(swoole_client $cli) {
    Assert::true(false, 'never here');
});
$cli->on("receive", function(swoole_client $cli, $data) {
    Assert::true(false, 'never here');
});
$cli->on("error", function(swoole_client $cli) { echo "connect timeout\n"; });
$cli->on("close", function(swoole_client $cli) { echo "close\n"; });
$cli->connect("11.11.11.11", 9000, 0.5);