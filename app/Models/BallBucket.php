<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BallBucket extends Model
{
    use HasFactory;

    public function ball()
    {
        return $this->belongsTo('App\Models\Ball');
    }

    public function bucket()
    {
        return $this->belongsTo('App\Models\Bucket');
    }
}
