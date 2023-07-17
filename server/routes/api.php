<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\DataController;
// use App\Http\Controllers\ShareController;
// use App\Http\Controllers\HistoryController;



Route::get('/auth',[AuthController::class,'index'])->name('login');
Route::post('/auth/signin', [AuthController::class, 'signin']);
Route::post('/auth/signup', [AuthController::class, 'signup']);


//blogs api
Route::controller(BlogController::class)
    ->prefix('/blogs')
    ->name('blogs.')
    ->group(function(){
        #show all blogs
        Route::get('/',  'index')->name('index');
        #get blog by id
        Route::get('/{id}', 'show')->name('show');
        #search blogs by name
        Route::get('/search/{name}', 'search')->name('search');
});
#show all comments of a blog
Route::get('/blogs/{id}/comments', [CommentController::class,'show'])->name('comments.show');
#show all vote of a blog
Route::get('/blogs/{id}/votes', [VoteController::class,'show'])->name('votes.show');
#show all view of a blog
Route::get('/blogs/{id}/views', [ViewController::class,'show'])->name('views.show');


#show all followe of a user
Route::get('/users/{id}/follow', [FollowController::class,'show'])->name('follows.show');

#get user profile
Route::get('/users/{id}', [UserController::class,'show'])->name('users.show');


#GET single blog data
Route::get('/data/{id}', [DataController::class,'getBlogData'])->name('datas.getBlogData');
#Home
Route::get('/data_newest', [DataController::class,'getNewestBlogs'])->name('datas.getNewestBlogs');

// Route::get('/data' , [DataController::class,'index']);

#Get dashboard
Route::get('/dashboard/{id}', [DataController::class,'getMyBlogs'])->name('datas.getMyblogs');


//auth API
Route::middleware('auth:sanctum')->group(function () {
    #signout
    Route::delete('/auth/signout', [AuthController::class, 'signout']);
    #store a blog
    Route::post('/blogs', [BlogController::class,'store'])->name('blogs.store');
    #update a blog
    Route::put('/blogs/{id}', [BlogController::class,'update'])->name('blogs.update');
    #delete a blog
    Route::delete('/blogs/{id}', [BlogController::class,'destroy'])->name('blogs.destroy');

   
    #store a comment
    Route::post('/blogs/{id}/comments', [CommentController::class,'store'])->name('comments.store');

    #vote
    Route::get('/blogs/{id}/votes/{type}', [VoteController::class,'store'])->name('votes.store');
    #View
    Route::get('/blogs/{id}/views', [ViewController::class,'store'])->name('views.store');
    #follow
    Route::get('/users/{id}/follow', [FollowController::class,'store'])->name('follows.store');

    #Get History
    Route::get('/history', [DataController::class,'getHistory'])->name('datas.getHistory');
    #Get User info
    Route::get('/current_user', [AuthController::class,'current_user']);
    #Get Follwing Blogs
    Route::get('/following',[DataController::class,'getFollowing'])->name('datas.getFollwing');
});


