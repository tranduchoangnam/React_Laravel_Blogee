<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
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
        $request->validate([
            'comment' => 'required',
        ]);
        $user_id=auth()->id();
        $request->merge(['user_id' => $user_id]);
        $request->merge(['blog_id' => $id]);
        // dd($request->all());
        return Comment::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Comment::where('blog_id', $id)->latest()->get();
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
