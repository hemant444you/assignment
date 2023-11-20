<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ball;
use \Auth;

class BallController extends Controller
{

    public function index()
    {
        $balls = Ball::all();
        return view('ball.index',compact('balls'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'name'    => 'required|unique:buckets',
            'volume'    => 'required|numeric',
        ];

        $ball = new Ball();
        if($request->id != ''){
            $ball = ball::where('id',$request->id)->first();
            if($request->name == $ball->name){
                $rules = [
                    'name'    => 'required',
                    'volume'    => 'required|numeric',
                ];
            }
            $msg = 'Ball Updated';
        }else{
            $ball->user_id = Auth::User()->id;
            $msg = 'Ball Added';
        }
        $validation = \Validator::make( $request->all(), $rules );
        if( $validation->fails() ) {
            return redirect()->back()->with('error',$validation->errors()->first());
        }
        $ball->name = $request->name;
        $ball->volume = $request->volume;
        $ball->save();

        return redirect()->route('ball.index')->with('success',$msg);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $ball = Ball::where('id',$id)->first();
        $ball->delete();

        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
