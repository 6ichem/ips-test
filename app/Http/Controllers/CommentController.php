<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $currentUser = auth()->user();

        $request->validate([
            'body' => 'required|string|max:255',
        ]);

        $data = [
            'body' => $request->input('body'),
            'user_id' => $currentUser->id,
        ];
        $comment = new Comment($data);
        $comment->save();

        CommentWritten::dispatch($comment);

        return response()->json(['message' => 'Comment created successfully'], 201);
    }
}
