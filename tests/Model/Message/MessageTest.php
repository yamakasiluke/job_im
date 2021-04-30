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

namespace Tests\Model;

use Prooph\ProophessorDo\Model\Group\Event\CreateGroup;
use Prooph\ProophessorDo\Model\Group\Event\EnterGroup;
use Prooph\ProophessorDo\Model\Group\Event\SendMessageToGroupMember;
use Prooph\ProophessorDo\Model\Group\Group;
use Prooph\ProophessorDo\Model\Group\GroupId;
use Prooph\ProophessorDo\Model\Message\Event\SendMessageToGroup;
use Prooph\ProophessorDo\Model\Message\Message;
use Prooph\ProophessorDo\Model\Message\MessageId;
use Prooph\ProophessorDo\Model\Message\MessageText;
use Prooph\ProophessorDo\Model\Message\Receiver;
use Prooph\ProophessorDo\Model\Message\Sender;
use Prooph\ProophessorDo\Model\User\UserId;
use Tests\Model\GroupTest;
use Tests\TestCase;

class MessageTest extends TestCase
{
//    public function it_user_send_message_to_group(): Message
//    {
//        $group = new GroupTest();
//        $group = $group->it_random_user_enter_a_group();
//
////        'message_text' => $messageText,
////            'sender_id' => $senderId,
////            'receiver_id' => $receiverId,
//        $messageText = MessageText::fromString("this is test message from test");
//        $senderId = $group->owner();
//        $receiverId = $group->groupId();
//        $messageId = MessageId::generate();
//        $message = Message::createMessage(
//            $messageId,
//            $sender = Sender::USER(),
//            $senderId,
//            $receiver = Receiver::GROUP(),
//            $receiverId,
//            $messageText
//        );
//
//        $this->assertInstanceOf(Message::class, $message);
//        $events = $this->popRecordedEvent($message);
//
//        $this->assertEquals(1, \count($events));
//        $this->assertInstanceOf(SendMessageToGroup::class, $events[0]);
//
//        $expectedPayload = [
//            'sender' => $sender->toString(),
//            'receiver' => $receiver->toString(),
//            'sender_id' => $senderId->toString(),
//            'receiver_id' => $receiverId->toString(),
//            'message_text' => $messageText->toString(),
//        ];
//        $this->assertEquals($expectedPayload, $events[0]->payload());
//        return $message;
//    }
//    /**
//     * @test
//     */
//    public function it_send_message_to_group_member(): Group
//    {
//        $group = new GroupTest();
//        $group = $group->it_random_user_enter_a_group();
//
//        $message = $this->it_user_send_message_to_group();
////        'group_id' => $groupId->toString(),
////            'message_text' => $messageText->toString(),
////            'message_id' => $messageId->toString(),
//        $members = [1=>1];
//        $group->sendMessageToGroupMember(
//            $group->groupId(),
//            $message->messageId(),
//            $message->messageText(),
//            $members
//        );
//        $this->assertInstanceOf(Group::class, $group);
//        $events = $this->popRecordedEvent($group);
//
//        $this->assertEquals(1, \count($events));
//        $this->assertInstanceOf(SendMessageToGroupMember::class, $events[0]);
//
//        $expectedPayload = [
//            'message_id' => $message->messageId()->toString(),
//            'message_text' => $message->messageText()->toString(),
//        ];
//        $this->assertEquals($expectedPayload, $events[0]->payload());
//
//        return $group;
//
//
//    }

}

