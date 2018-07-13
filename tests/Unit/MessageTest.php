<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\UsersAdmins;

/**
 * @group message
 */
class MessageTest extends TestCase
{
    use DatabaseMigrations;
    use UsersAdmins;

    public function testCanstoreMessage()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $user3 = $this->fetchUser();

        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $recipients = [$user2, $user3];

        $message = $this->fetchMessage($circle, $user, $recipients);

        $this->assertDatabaseHas('messages', [
            'user_id' => $user->id,
            'circle_id' => $circle->id,
        ]);

        $message = $this->fetchMessage($circle, null, $recipients);

        $this->assertDatabaseHas('messages', [
            'user_id' => null,
            'circle_id' => $circle->id,
        ]);
    }

    public function testUserCanReadMessage()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $user3 = $this->fetchUser();
        $user4 = $this->fetchUser();

        $faker = $this->fetchFaker();
        $circle = $this->fetchCircle($user);

        $recipients = [$user2, $user3];

        $message = $this->fetchMessage($circle, $user, $recipients);

        $this->assertTrue($message->visibleBy($user3));
        $this->assertFalse($message->visibleBy($user4));

        $message->show_to_all = true;
        $message->save();

        $this->assertTrue($message->visibleBy($user3));
        $this->assertTrue($message->visibleBy($user4));
    }

    public function testUserCanWriteMessage()
    {
        $user = $this->fetchUser();
        $user2 = $this->fetchUser();
        $user3 = $this->fetchUser();
        $user4 = $this->fetchUser();

        $faker = $this->fetchFaker();

        $circle = $this->fetchCircle($user);

        $body = $faker->text;
        $show_to_all = false;

        $message = $circle->storeMessage($user, $body, $show_to_all);

        $this->assertTrue(is_null($message));

        $circle->joinWithDefaults($user2);
        $circle->joinWithDefaults($user3);

        $circle = $circle->refresh();

        $message = $circle->storeMessage($user, $body, $show_to_all);

        $this->assertFalse(is_null($message));

        $this->assertDatabaseHas('messages', [
            'user_id' => $user->id,
            'recipients' => sprintf('[%d,%d]', $user2->id, $user3->id),
            'circle_id' => $circle->id,
        ]);

        $circle->joinWithDefaults($user4);

        $this->assertTrue($message->visibleBy($user2));
        $this->assertTrue($message->visibleBy($user3));
        $this->assertFalse($message->visibleBy($user4));

        $vis_messages = $circle->visibleMessages($user);
        $this->assertEquals(1, count($vis_messages));

        $vis_messages = $circle->visibleMessages($user4);
        $this->assertEquals(0, count($vis_messages));
    }
}
