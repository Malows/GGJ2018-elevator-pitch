<?php

namespace App\Http\Controllers;

use App\Score;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Score::sortBy('position');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scores = Score::sortBy('position');

        $lowerScores = $scores->filter(function ($x) use ($request) {
            return $x < $request->score;
        });

        if ($lowerScores->isEmpty()) return response()->json();

        $dealed = $this->_deal($lowerScores);

        $newScore = new Score($request->all());
        $newScore->postition = $dealed['highestPosition'];
        $newScore->save();

        $dealed['toUpdate']->each(function ($x) { $x->save(); });
        $dealed['toDelete']->delete();

        return $newScore;
    }

    /**
     * Deal the scores
     *
     * @param  \Illuminate\Database\Eloquent\Collection
     * @return array
     */
    private function _deal(Collection $elements)
    {
        $elements = $elements->sortBy('position');
        $toUpdate = $elements->take($elements->count() - 1);

        return [
            'highestPosition' => $elements->first()->position,
            'toUpdate' => $toUpdate->map(function ($x) { $x->position += 1; return $x; }),
            'toDelete' => $elements->last()
        ];
    }
}