<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\FootballMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:500'],
        ]);

        Comment::create([
            'match_id' => $match->id,
            'user_id' => $request->user()->id,
            'content' => $data['content'],
        ]);

        return back()->with('status', 'Comentari publicat.');
    }
}
