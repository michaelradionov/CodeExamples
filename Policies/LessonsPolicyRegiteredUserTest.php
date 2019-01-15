<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonsPolicyRegiteredUserTest extends TestCase
{

    use DatabaseTransactions;

    protected $user;

    public function SetUp()
    {
        parent::SetUp();
        $this->user = factory(User::class)->create();
    }

//    If you are not a paid student then you get only Intro Webinar, nothing more 😭

    public function testRegistredUserCanNotSeeLessonsPage()
    {
        $response = $this->actingAs($this->user)->get('/lessons');
        $response->assertRedirect(route('wait4webinar'));
    }

    public function testRegisteredUserCanNotSeeLessonTitle()
    {
        $response = $this->actingAs($this->user)->get('/lessons')->assertRedirect(route('wait4webinar'));

    }

    public function testRegisteredUserCanNotSeeProfilePage()
    {
        $responce = $this->actingAs($this->user)->get(route('profile'))->assertRedirect(route('wait4webinar'));
    }

}
