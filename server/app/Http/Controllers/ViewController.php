<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\View;

class ViewController extends Controller
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
        $viewed=View::where('blog_id', $id)->where('user_id', auth()->id())->first();
        if($viewed){
            return ['message'=>'viewed','status'=>200];
        }
        $user_id=auth()->id();
        $request->merge(['user_id' => $user_id,'blog_id' => $id]);
        // dd($request->all());
        return View::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return View::where('blog_id', $id)->count();
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
    public function destroy(string $id)
    {
        //
    }
}
