<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    protected $fillable = [ 'name', 'player', 'score' ];

    protected $dates = [ 'created_at', 'updated_at' ];
}
