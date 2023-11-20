<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    use HasFactory;

    public function ball_buckets()
    {
        return $this->hasMany('App\Models\BallBucket');
    }
}
