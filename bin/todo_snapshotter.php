<?php

/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\ProophessorDo;

use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\ProophessorDo\Model\Todo\Todo;
use Prooph\ProophessorDo\Model\Todo\TodoList;
use Prooph\SnapshotStore\SnapshotStore;
use Prooph\Snapshotter\SnapshotReadModel;
use Prooph\Snapshotter\StreamSnapshotProjection;

\chdir(\dirname(__DIR__));

require_once 'vendor/autoload.php';

$container = require 'config/container.php';

$projectionManager = $container->get(ProjectionManager::class);
/* @var ProjectionManager $projectionManager */

$readModel = new SnapshotReadModel(
    $container->get(TodoList::class),
    new AggregateTranslator(),
    $container->get(SnapshotStore::class),
    [
        Todo::class,
    ]
);

$projection = new StreamSnapshotProjection(
    $projectionManager->createReadModelProjection(
        'todo_snapshotter',
        $readModel
    ),
    'todo_stream'
);

$projection();
