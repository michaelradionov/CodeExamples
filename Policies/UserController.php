<?php

namespace App\Http\Controllers;

use App\Events\UserRegistred;
use App\Mail\UserRegisteredMailToUser;
use App\User;
use BeyondCode\EmailConfirmation\Events\Confirmed;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

  /**
   * How much lessons do we need to complete to access a given lesson
   * @param \App\Lesson $lesson
   * @return int
   */
  public function undone_lessons(Lesson $lesson)
  {
      // We need to have all of the previous lesson's practices and testings passed to have access to a given one.
      $totalNumberOfLessons = config('app.total_number_of_lessons');
      $range = range(1, ($totalNumberOfLessons - 1));
      $needed = array_filter($range, function($key)use($lesson){
         return $key < $lesson->lesson_number;
      });

      // Arrays of practices and testings passed by current user
      $practices_down = $this->practices_down()->toArray();
      $testings_down = $this->testings_down()->toArray();

      // Arrays of system-wide blocked practices and testings
      $blocked_practices = $this->blocked_practices()->toArray();
      $blocked_testings = $this->blocked_testings()->toArray();

      // Array of practices and testings needed.
      $undone_practices = array_diff($needed, $practices_down, $blocked_practices);
      $undone_testings = array_diff($needed, $testings_down, $blocked_testings);

      // We return total number of undone tasks. It is enough for our purposes.
      return count($undone_practices) + count($undone_testings);
  }

}
