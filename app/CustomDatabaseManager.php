<?php
namespace App;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;

class CustomDatabaseManager extends DatabaseManager{
    public $workerId;

    public function __construct($app, ConnectionFactory $factory)
    {
        $app->make("workerid");
        $this->workerId = $app->make("workerid");;
        parent::__construct($app, $factory);
    }
    public function wid(){
        return $this->workerId;
    }
}
