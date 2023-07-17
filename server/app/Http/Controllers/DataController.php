<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Vote;
use App\Models\View;

class DataController extends Controller
{   
    public function getBlogData(string $id){
        // $blog=Blog::find($id);
        // $user=User::find($blog->user_id);
        // $comments=Comment::where('blog_id',$blog->id)->get();
        // $votes=Vote::where('blog_id',$blog->id)->get();
        // $views=View::where('blog_id',$blog->id)->get();
        $blog=Blog::find($id);
        $owner=$blog->user()->first();
        $commentList=$blog->comments()->get();
        $comments=collect();
        foreach ($commentList as $comment){
            $user=$comment->user()->first();
            $followers=$user->follower()->count();
            $comments->push(['comment'=>$comment,'user'=>$user,'followers'=>$followers]);
        }
        // return $comments;
        $countUpvote=$blog->votes()->where('vote','up')->count();
        $countDownvote=$blog->votes()->where('vote','down')->count();
        $countShare=0;
        $countComment=$comments->count();
        $countView=$blog->views()->count();
        $voted=$blog->votes()->get();
        // dd($voted);
        if (!$voted->isEmpty()){
        if ($voted[0]->vote == 'up') {
            $voted = 1;
        } elseif ($voted[0]->vote == 'down') {
            $voted = -1;
        } else {
            $voted = 0;
        }}else {
            $voted = 0;
        }
        return ['blog'=>$blog,
                'owner'=>$owner,
                'countUpvote'=>$countUpvote,
                'countDownvote'=>$countDownvote,
                'countShare'=>$countShare,
                'countComment'=>$countComment,
                'countView'=>$countView,
                'voted'=>$voted,
                'comments'=>$comments,
                ];
        // dd($comments);
        // $data=[
        //     'blog'=>$blog,
        //     'owner'=>$user,
        //     'countUpvote'=>$votes->show($blog->id)['upvote'],
        //     'countDownvote'=>$votes->show($blog->id)['downvote'],
        //     'comments'=>$comments,
        //     'votes'=>$votes,
        //     'countView'=>$views,

        // ];
        
        return $data;
    }
    public function getNewestBlogs(){
        $blogs=Blog::orderBy('created_at','desc')->get();
        $data=collect();
        foreach($blogs as $blog){
            $data->add($this->getBlogData($blog->id));
        }
        return $data;
    }
    public function getMyBlogs(){
        $blogs=Blog::where('user_id',auth()->id())->get();
        $data=collect();
        foreach($blogs as $blog){
            $data->add($this->getBlogData($blog->id));
        }
        return ['user'=>auth()->user(),'blog'=>$data];
    }
    public function getHistory(){
        $views=View::where('user_id',auth()->id())->get();
        $data=collect();
        foreach($views as $view){
            $data->add($this->getBlogData($view->blog_id));
        }
        return $data;
    }
    public function getFollowing(){
        $followings=Follow::where('follower_id',auth()->id())->get();
        $data=collect();
        foreach($followings as $following){
            $data->add($this->getBlogData($following->blog_id));
        }
        return $data;
    }
}
