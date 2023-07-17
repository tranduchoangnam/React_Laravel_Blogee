<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;

class VoteController extends Controller
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
    public function store(Request $request, string $id,string $type)
    {   
        $voted=Vote::where('blog_id', $id)->where('user_id', auth()->id())->first();
        if($voted){
            Vote::destroy($voted->id);
            if($voted->vote==$type){
                return Vote::where('blog_id', $id)->where('vote',$type)->count();
            }
        }
        $user_id=auth()->id();
        $request->merge(['user_id' => $user_id,'blog_id' => $id,'vote' => $type]);
        // dd($request->all());
        Vote::create($request->all());
        return Vote::where('blog_id', $id)->where('vote',$type)->count();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $upvote=Vote::where('blog_id', $id)->where('vote','up')->count();
        $downvote=Vote::where('blog_id', $id)->where('vote','down')->count();
        return ['upvote'=>$upvote,'downvote'=>$downvote];
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
    public function update(Request $request, string $blog_id,string $type)
    {
        $request->validate([
            'content' => 'required',
        ]);
        $user_id=auth()->id();
        $request->merge(['user_id' => $user_id,'blog_id' => $blog_id,'type' => $type]);
        // dd($request->all());
        return Vote::update($request->all());
    }
    /**
     * Check viewed
     */

     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Vote::destroy($id);
    }
}
