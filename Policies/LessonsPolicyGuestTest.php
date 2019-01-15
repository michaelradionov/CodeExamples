<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonsPolicyGuestTest extends TestCase
{

    use DatabaseTransactions;



    public function testGuestCanNotSeeLessons()
    {
        $response = $this->get('/lessons');
        $response->assertRedirect('/login');
    }

    public function testGuestCanNotSeeProfilePage()
    {
        $response = $this->get(route('profile'));
        $response->assertRedirect('/login');
    }
}
