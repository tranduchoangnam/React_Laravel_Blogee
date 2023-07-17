<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Vote;
use App\Models\View;

class DataController extends Controller
{   
    public function getBlogData(string $id){
     
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
        $current_user=Auth::user();
        if($current_user) ($owner->follower()->get()->where('follower_id',$current_user->id)->count())?$followed=true:$followed=false;
        else $followed=false;
       
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
                'followed'=>$followed,
                ];
        
    }
    public function getNewestBlogs(){
        $blogs=Blog::orderBy('created_at','desc')->get();
        $data=collect();
        foreach($blogs as $blog){
            $data->push($this->getBlogData($blog->id));
        }
        return $data;
    }
    public function getMyBlogs(string $id){
        $user=User::find($id);
        $blogs=$user->blog()->get();
        $data=collect();
        foreach($blogs as $blog){
            $data->push($this->getBlogData($blog->id));
        }
        return ['user'=>$user,'blog'=>$data];
    }
    public function getHistory(){
        $views=View::where('user_id',auth()->id())->get();
        $data=collect();
        foreach($views as $view){
            $data->push($this->getBlogData($view->blog_id));
        }
        return $data;
    }
    public function getFollowing(){
        $followings=auth()->user()->following()->get();
        $data=collect();
        foreach($followings as $following){
            $user=$following->following()->first();
            foreach($user->blog()->get() as $blog){
                $data->push($this->getBlogData($blog->id));
            }
        }
        return $data;
    }
}
