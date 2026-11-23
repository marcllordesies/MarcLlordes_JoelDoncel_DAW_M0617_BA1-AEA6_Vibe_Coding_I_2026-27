<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Prediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $data = $request->validate([
            'choice' => ['required', 'in:1,X,2'],
        ]);

        Prediction::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'match_id' => $match->id,
            ],
            [
                'choice' => $data['choice'],
            ]
        );

        return back()->with('status', 'Prediccio desada.');
    }
}
