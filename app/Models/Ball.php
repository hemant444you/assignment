<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ball extends Model
{
    use HasFactory;

    public function ball_buckets()
    {
        return $this->hasMany('App\Models\BallBucket');
    }

    public function balls_quantity()
    {
        return $this->ball_buckets->sum('quantity');
    }
}
