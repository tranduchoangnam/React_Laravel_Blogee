<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Blog::latest()->get();
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
    public function store(Request $request)
    {   
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'tags' => 'required',
            'photo' => 'required',
        ]);
        $user_id=auth()->id();
        $path=$request->file('photo')->store('public');
        $photoUrl= substr($path, strlen('public/'));
        $data=$request->all();
        $data['user_id']=$user_id;
        $data['photo']=$photoUrl;
        return $data;
        // dd($data);
        // dd($request->all());
        return Blog::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Blog::find($id);
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
        $blog = Blog::find($id);
        $blog->update($request->all());
        return $blog;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {   
        $blog=Blog::find($id);
        if (!$blog) return response()->json([
            'message' => 'Blog not found'
        ], 400);
        $user_id=auth()->id();
        $request->merge(['user_id' => $user_id]);
        dd($user_id,$blog->user_id);
        // if($user_id ){
        //     return response()->json([
        //         'message' => 'You have no permission to delete this blog'
        //     ], 403);
        // }
        return Blog::destroy($id);
    }

    /**
     * Search for a term in the blog title or content.
     */
    public function search(string $name)
    {
        return Blog::where('content', 'like', '%'.$name.'%')->get();
    }
    

}
