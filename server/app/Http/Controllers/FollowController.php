<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {  
        $id=(int)$id;
        $owner=User::find($id);
        $user_id=auth()->id();
        $followed=$owner->follower()->get()->where('follower_id',$user_id)->first();
        if($followed){
            $this->destroy($followed->id);
            return ['message'=>'follow removed','status'=>200];
        }
        $request->merge(['follower_id' => $user_id,'following_id' => $id]);
        // dd($request->all());
        return Follow::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $id=(int)$id;
        return Follow::where('following_id', $id)->count();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Follow::destroy($id);
    }
}
