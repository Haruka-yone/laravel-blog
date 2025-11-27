<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// middleware Arth verifies the user og your applicatioin
// If the user not authenticated, redirect the user to the login page
// If uthr user is authenticated,will allow reqest to proceed into to app
Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [PostController::class, 'index'])->name('index');

    // POST
    Route::group(['prefix' => 'post', 'as' => 'post.'], function(){      
        Route::get('/create',[PostController::class, 'create'])->name('create');
        //         /post/create                                  post.create
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/show', [PostController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [PostController::class, 'destroy'])->name('destroy');

    });

    // COMMENT
    Route::group(['prefix' => 'comment', 'as' => 'comment.'], function(){
        Route::post('/{post_id}/store', [CommentController::class, 'store'])->name('store');
        Route::delete('/{id}/destroy', [CommentController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/update', [CommentController::class, 'update'])->name('update');
    });

    // USER
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function(){
        Route::get('/', [UserController::class, 'show'])->name('show');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/update',[UserController::class, 'update'])->name('update');
        Route::get('/{id}/show', [UserController::class, 'specificshow'])->name('specificshow');
    });

    

});