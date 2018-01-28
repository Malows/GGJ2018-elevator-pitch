<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyScore extends Model
{
    protected $fillable = [ 'score', 'position', 'player', 'influencer_id' ];

    protected $dates = [ 'created_at', 'updated_at' ];
}
