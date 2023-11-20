<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bucket;
use \Auth;

class BucketController extends Controller
{

    public function index()
    {
        $buckets = Bucket::all();
        return view('bucket.index',compact('buckets'));
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

        $bucket = new Bucket();
        if($request->id != ''){
            $bucket = Bucket::where('id',$request->id)->first();
            $bucket->space = $bucket->space;
            if($request->name == $bucket->name){
                $rules = [
                    'name'    => 'required',
                    'volume'    => 'required|numeric',
                ];
            }
            $msg = 'Bucket Updated';
        }else{
            $bucket->user_id = Auth::User()->id;
            $bucket->space = $request->volume;
            $msg = 'Bucket Added';
        }
        $validation = \Validator::make( $request->all(), $rules );
        if( $validation->fails() ) {
            return redirect()->back()->with('error',$validation->errors()->first());
        }
        $bucket->name = $request->name;
        $bucket->volume = $request->volume;
        $bucket->status = 'New';
        $bucket->save();

        return redirect()->route('bucket.index')->with('success',$msg);
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
        $bucket = Bucket::where('id',$id)->first();
        $bucket->delete();

        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
