<?php

use Prooph\ProophessorDo\Model;

return [
    /*
    |--------------------------------------------------------------------------
    | Command Buses
    |
    | Each entry will define a different command bus in the application. It can
    | be retrieved with `ServiceBus::commandBus('index')`. The default bus will
    | be bound to the the CommandBus class and facade.
    |
    | Each command bus can configure:
    | - message_factory: Defaults to FQCNMessageFactory, if not provided.
    | - action_event_emitter: Defaults to ProophActionEventEmitter, if not provided.
    | - plugins: A list of plugins to add to the bus.
    | - router: Configuration for the router plugin of the bus.
    |
    | Router configurations:
    | - type: The service ID or FQCN of the router. Defaults to CommandRouter if
    |         not provided.
    | - async_switch: The service ID or FQCN of the async message producer. Optional
    | - routes: A list of messageName => handler
    |
    |--------------------------------------------------------------------------
    */
    'command_buses' => [
        'default' => [
            'message_factory' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
            'action_event_emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
            'plugins'         => [

            ],
            'router' => [
                'type' => \Prooph\ServiceBus\Plugin\Router\CommandRouter::class,
                'routes' => [

                    Model\User\Command\RegisterUserCommand::class => Model\User\Handler\RegisterUserHandler::class,
                    Model\User\Command\UserAnonymousCommand::class => Model\User\Handler\UserAnonymousHandler::class,
                    Model\User\Command\UserLoginCommand::class => Model\User\Handler\UserLoginHandler::class,
                    Model\User\Command\UserLogoutCommand::class => Model\User\Handler\UserLogoutHandler::class,
                    Model\User\Command\UserOfflineCommand::class => Model\User\Handler\UserOfflineHandler::class,
                    Model\User\Command\UserOnlineCommand::class => Model\User\Handler\UserOnlineHandler::class,

                    Model\AccessToken\Command\ApplyAccessTokenCommand::class => Model\AccessToken\Handler\ApplyAccessTokenHandler::class,

                    Model\Group\Command\CreateGroupCommand::class => Model\Group\Handler\CreateGroupHandler::class,
                    Model\Group\Command\EnterGroupCommand::class => Model\Group\Handler\EnterGroupHandler::class,
                    Model\Group\Command\ExitGroupCommand::class => Model\Group\Handler\ExitGroupHandler::class,
                    Model\Group\Command\SendMessageToGroupMemberCommand::class => Model\Group\Handler\SendMessageToGroupMemberHandler::class,


                    Model\Message\Command\SendMessageCommand::class => Model\Message\Handler\SendMessageHandler::class,
                    Model\Message\Command\SendMessageToGroupCommand::class => Model\Message\Handler\SendMessageToGroupHandler::class,






                    Model\Todo\Command\PostTodo::class => Model\Todo\Handler\PostTodoHandler::class,
                    Model\Todo\Command\MarkTodoAsDone::class => Model\Todo\Handler\MarkTodoAsDoneHandler::class,
                    Model\Todo\Command\ReopenTodo::class => Model\Todo\Handler\ReopenTodoHandler::class,
                    Model\Todo\Command\AddDeadlineToTodo::class => Model\Todo\Handler\AddDeadlineToTodoHandler::class,
                    Model\Todo\Command\AddReminderToTodo::class => Model\Todo\Handler\AddReminderToTodoHandler::class,
                    Model\Todo\Command\MarkTodoAsExpired::class => Model\Todo\Handler\MarkTodoAsExpiredHandler::class,
                    Model\Todo\Command\RemindTodoAssignee::class => Model\Todo\Handler\RemindTodoAssigneeHandler::class,
                    Model\Good\Command\PublishGoodCommand::class => Model\Good\Handler\PublishGoodHandler::class,
                ]
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Buses
    |
    | Each entry will define a different command bus in the application. It can
    | be retrieved with `ServiceBus::eventBus('index')`. The default bus will
    | be bound to the the EventBus class and facade.
    |
    | Each command bus can configure:
    | - message_factory: Defaults to FQCNMessageFactory, if not provided.
    | - action_event_emitter: Defaults to ProophActionEventEmitter, if not provided.
    | - plugins: A list of plugins to add to the bus.
    | - router: Configuration for the router plugin of the bus.
    |
    | Router configurations:
    | - type: The service ID or FQCN of the router. Defaults to EventRouter if
    |         not provided.
    | - routes: A list of messageName => [ listener1, listener2 ]
    |
    |--------------------------------------------------------------------------
    */
    'event_buses'   => [
        'default' => [
            'message_factory' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
            'action_event_emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
            'plugins'         => [
                \Prooph\ServiceBus\Plugin\InvokeStrategy\OnEventStrategy::class
            ],
            'router' => [
                'type' => \Prooph\ServiceBus\Plugin\Router\EventRouter::class,
                'routes' => [
                    Model\Todo\Event\TodoAssigneeWasReminded::class => [
                        \Prooph\ProophessorDo\ProcessManager\SendTodoReminderMailProcessManager::class,
                    ],
                    Model\Todo\Event\TodoWasMarkedAsExpired::class => [
                        \Prooph\ProophessorDo\ProcessManager\SendTodoDeadlineExpiredMailProcessManager::class,
                    ],
                ]
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Buses
    |
    | Each entry will define a different command bus in the application. It can
    | be retrieved with `ServiceBus::queryBus('index')`. The default bus will
    | be bound to the the QueryBus class and facade.
    |
    | Each command bus can configure:
    | - message_factory: Defaults to FQCNMessageFactory, if not provided.
    | - action_event_emitter: Defaults to ProophActionEventEmitter, if not provided.
    | - plugins: A list of plugins to add to the bus.
    | - router: Configuration for the router plugin of the bus.
    |
    | Router configurations:
    | - type: The service ID or FQCN of the router. Defaults to QueryRouter if
    |         not provided.
    | - routes: A list of messageName => handler
    |
    |--------------------------------------------------------------------------
    */
    'query_buses'   => [
        'default' => [
            'message_factory' => \Prooph\Common\Messaging\FQCNMessageFactory::class,
            'action_event_emitter' => \Prooph\Common\Event\ProophActionEventEmitter::class,
            'plugins'         => [

            ],
            'router' => [
                'type' => \Prooph\ServiceBus\Plugin\Router\QueryRouter::class,
                'routes' => [

                ]
            ]
        ],
    ],
];
