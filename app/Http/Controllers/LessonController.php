<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function get(Request $request, Lesson $lesson)
    {
        return $lesson;
    }
}
