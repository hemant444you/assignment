<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bucket;
use App\Models\Ball;
use App\Models\BallBucket;
use \Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $balls = Ball::where('user_id',Auth::User()->id)->get();
        $buckets = Bucket::where('user_id',Auth::User()->id)->get();
        return view('home',compact('balls','buckets'));
    }

    public function suggestion(Request $request)
    {

        $balls_volume = 0;
        $bucket_volume = Bucket::where('user_id',Auth::User()->id)->sum('volume');
        foreach($request->ball_id as $key => $id){
            $ball = Ball::find($id);
            $quantity = $request->quantity[$key];
            // check if 0 enters
            $ball_volume = $ball->volume * $quantity;
            $balls_volume = $balls_volume + $ball_volume;
        }

        // if($balls_volume > $bucket_volume){
        //     return redirect()->back()->with('error','Ball Volume is more than Bucket volume');
        // }

        $buckets = Bucket::where('user_id',Auth::User()->id)->orderBy('space','desc')->get();
        foreach($buckets as $bucket){
            $bucket->space = $bucket->volume;
            $bucket->save();
        }
        $error = '';
        $error_msg = '';
        foreach($request->ball_id as $key => $id){
            $ball = Ball::find($id);
            $ball_buckets = BallBucket::where('ball_id',$ball->id)->get();
            foreach($ball_buckets as $ball_bucket){
                $ball_bucket->delete();
            }
            $quantity = $request->quantity[$key];
            if($quantity > 0){
                $msg = $this->fill_balls($ball, $quantity);
                if($msg){
                    $error = 'yes';
                }
                $error_msg = $error_msg .' '. $msg;
            }
        }
        if($error == 'yes'){
            return redirect()->back()->with('error',$error_msg. ' can not fill');
        }
        return redirect()->route('home')->with('success','Balls occupied successfully');

    }

    public function fill_balls($ball, $quantity)
    {
        $bucket = Bucket::where('user_id',Auth::User()->id)->where('space', '>=', $ball->volume)->where('status','Used')->orderBy('space','desc')->first();
        if(!$bucket){
            $bucket = Bucket::where('user_id',Auth::User()->id)->where('space', '>=', $ball->volume)->orderBy('space','desc')->first();
        }
        if(!$bucket){
            return $quantity.' '.$ball->name.' Ball';
        }
        $can_fill = floor($bucket->space / $ball->volume);
        
        $fillable_quantity = $quantity;
        $remaining_quantity = 0;
        if($quantity > $can_fill){
            $fillable_quantity = $can_fill;
            $remaining_quantity = $quantity - $can_fill;
        }
        $ball_bucket = new BallBucket();
        $ball_bucket->bucket_id = $bucket->id;
        $ball_bucket->ball_id = $ball->id;
        $ball_bucket->quantity = $fillable_quantity;
        $ball_bucket->save();
        $bucket->space = $bucket->space - $ball_bucket->quantity * $ball->volume;
        $bucket->status = 'Used';
        $bucket->save();

        if($remaining_quantity > 0){
            return $this->fill_balls($ball, $remaining_quantity);
        }
    }
}
