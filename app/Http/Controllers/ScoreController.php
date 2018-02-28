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
        $scores = $this->agregar_y_ordenar(
            Score::orderBy('score', 'desc')->get(),
            $request
        );

        // daily section
        $this->handleDailyScore($request);

        // check the influencer
        $this->handleInfluencer($request);

        return $scores;
    }



    private function handleInfluencer(Request $request)
    {
        $influencer = Influencer::find($request->influencer_id);
        if ($influencer->score < $request->score) {
            $influencer->score = $request->score;
            $influencer->player = $request->player;
            $influencer->save();
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
        $dailys = DailyScore::orderBy('score'. 'desc')->get();
        $dailys->each(function ($x, $key) { $x->position = $key; $x->save(); });
        if ($dailys->count() > 20) {
            $dailys = $dailys->sortByDesc('score');
            $dailys->slice(20)->each(function ($x) { $x->delete(); });
        }
    }

    private function agregar_y_ordenar(Collection $elements, Request $request)
    {
        dd($elements);

        $newScore = new Score($request->all());
        $newScore->position = 0;

        dump($newScore);

        $elements->add($newScore);
        $elements = $elements->sortByDesc('score');

        dump($elements);

        $buenos = $elements->slice(0, 20);

        dump($buenos);

        $buenos = $buenos->each(function ($x, $key) {
            $x->position = $key;
            $x->save();
        });

        $malos = $elements->slice(20)->pluck('id');
        if (count($malos)) Score::destroy($malos);

        return array_values($buenos->toArray());
    }
}