<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [ 'score', 'position', 'player', 'influencer_id' ];

    protected $dates = [ 'created_at', 'updated_at' ];
}
