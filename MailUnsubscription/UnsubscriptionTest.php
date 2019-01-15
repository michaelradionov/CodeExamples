<?php

namespace Tests\Feature;

use App\Mail\UserUnsubscribedMailToAdmin;
use App\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;


class UnsubscriptionTest extends TestCase
{
    use DatabaseTransactions;


    public function testUserCanUnsubscribe()
    {
        Mail::fake();

        // Creating registered subscribed user and persist it in DB
        $user = factory(User::class)->make();
        $user->unsubscribed = 0;
        $user->save();

        // Hitting endpoint
        $response = $this->hitUnsubAPI($user->email);

        // Assert that response is Ok
        $response->assertStatus(200);
        $response->assertJson(['output' => 'User unsubscribed']);

        // Assert user unsubscribed in DB
        $user = $user->fresh(); // refreshing model from DB
        $this->assertEquals($user->unsubscribed, 1);

        // Assert admin notified about that
        Mail::assertSent(UserUnsubscribedMailToAdmin::class);

    }


    public function hitUnsubAPI($email='')
    {
        return $this->post(route('api.unsub'), [
            'event-data' => [
                'recipient' => $email
            ]
        ]);
    }


    public function testUnsubscriptionShouldFail()
    {
        // No data given
        $response = $this->hitUnsubAPI();
        $response->assertStatus(406);
        $response->assertJson(['output' => 'No data in request']);

        // User not found
        $fakeEmail = \Faker\Factory::create()->email;
        $response = $this->hitUnsubAPI($fakeEmail);
        $response->assertStatus(404);
        $response->assertJson(['output' => 'User not found']);

        // User already unsubscribed
        $user = factory(User::class)->make();
        $user->unsubscribed = 1;
        $user->save();
        $response = $this->hitUnsubAPI($user->email);
        $response->assertStatus(402);
        $response->assertJson(['output' => 'User already unsubscribed']);

    }
}
