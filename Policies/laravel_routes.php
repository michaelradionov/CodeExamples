<?php

// Lessons
Route::get('/lessons', 'LessonController@index')->name('lessons')->middleware(['auth']);

// Lesson
Route::prefix('/lessons/{lesson}')->middleware(
    [
        'auth',
        'can:userHasRole', // is a user paid
        'can:userDoneEnoughLessons,lesson'
    ]
)->group(
    function () {

        // Lesson webinar
        Route::get('/webinar', 'LessonController@renderWebinar')->middleware('can:webinarAvailable,lesson')->name('lessonWebinar');

        // Lesson slides
        Route::get('/presentation', 'LessonController@renderPresentation')->middleware(['can:presentationAvailable,lesson'])->name('lessonPresentation');

        // Lesson practice
        Route::prefix('/practice')->middleware(['can:practiceAvailable,lesson'])->group(
            function () {
                Route::get('/', 'LessonController@renderPractice')->name('lessonPractice');
                Route::get('/worker', 'PracticeWorker@run')->name('worker');
            }
        );

        // Lesson testing
        Route::prefix('/testing')->middleware(['can:practiceAvailable,lesson'])->group(function () {
            Route::get('/', 'LessonController@renderTesting')->name('renderTesting');
            Route::post('/', 'LessonController@processTestingResult')->name('processTestingResult');
            }
        );

    }
);
