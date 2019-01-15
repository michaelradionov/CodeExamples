<?php

namespace Tests\Feature;

use App\Lesson;
use App\PracticeTry;
use App\TestingTry;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonsPolicyStudentTest extends TestCase
{

    use DatabaseTransactions;

    protected $student;

    public function SetUp()
    {
        parent::SetUp();
        $this->student = factory(User::class)->create();
        // When a user pays the course we give him a role called student
        $this->student->role()->attach(1);
    }

    public function testStudentCanSeeLessonsListing()
    {
        $response = $this->actingAs($this->student)->get(route('lessons'))->assertStatus(200);
    }

    public function testStudentCanSeeFirstLessonsWebinar()
    {
        $firstLesson = Lesson::first();
        $response = $this->actingAs($this->student)->get(route('lesson',$firstLesson));
        $response->assertRedirect(route('lessonWebinar', $firstLesson));
    }

    public function testStudentCanNotSeeSecondLessonsWebinarIfPreviousLessonIsNotPassed()
    {
        $secondLesson = Lesson::get()[1];
        $response = $this->actingAs($this->student)->get(route('lesson',$secondLesson));
        $response->assertStatus(403);
    }

    public function testStudentCanSeeSecondLessonsWebinarIfPreviousLessonIsPassed()
    {
        $firstLesson = Lesson::first();
        $secondLesson = Lesson::get()[1];

        // Successful testing of first lesson
        $firstLesson->testing_tries()->save(
            $successedTestingTry = factory(TestingTry::class)->create(['percent' => 80])
        );
        $this->student->testing_tries()->save($successedTestingTry);

        // Successful practice of first lesson
        $firstLesson->practice_tries()->save(
            $successedPracticeTry = factory(PracticeTry::class)->create()
        );
        $this->student->practice_tries()->save($successedPracticeTry);

        // Trying to reach out to second lesson
        $response = $this->actingAs($this->student)->get(route('lesson',$secondLesson));
        $response->assertStatus(200);
    }

}
