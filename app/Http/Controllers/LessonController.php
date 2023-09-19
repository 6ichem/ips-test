<?php

namespace App\Http\Controllers;

use App\Events\LessonWatched;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function watch(Request $request, Lesson $lesson)
    {
        $currentUser = auth()->user();
    
        if (!$currentUser->lessons->contains($lesson)) {
            $currentUser->lessons()->attach($lesson, ['watched' => true]);
        }

        LessonWatched::dispatch($lesson, $currentUser);

        return $lesson;
    }
}
