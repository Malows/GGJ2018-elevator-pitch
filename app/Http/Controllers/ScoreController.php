<?php

namespace App\Http\Controllers;

use App\DailyScore;
use App\Influencer;
use App\Score;
use Carbon\Carbon;
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
        return Score::orderBy('position')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scores = Score::orderBy('position')->get();

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

        // daily section
        $this->handleDailyScore($request);

        // check the influencer
        $this->handleInfluencer($request);

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

    private function handleInfluencer(Request $request)
    {
        $influencer = Influencer::find($request->influencer_id);
        if ($influencer->score < $request->score) {
            $influencer->score = $request->score;
            $influencer->player = $request->player;
        }
    }

    private function handleDailyScore(Request $request)
    {
        // limpio las entradas viejas
        $this->cleanOlderDailys();

        //creo uno nuevo
        $newDailyScore = new DailyScore($request->all());
        $newDailyScore->position = 0;
        $newDailyScore->save();

        // actualizo las posiciones
        $this->updateDailysPosition();
    }

    private function cleanOlderDailys()
    {
        $yesterday = Carbon::now()->subHours(24);
        DailyScore::where('created_at', '<', $yesterday)->delete();
    }

    private function updateDailysPosition()
    {
        $dailys = DailyScore::orderBy('score')->get();
        $dailys->each(function ($x, $key) { $x->position = $key + 1; $x->save(); });
    }
}