<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // One to Many (Inverse)
    // To get the owner of the post
    public function user(){
        return $this->belongsTo(User::class);
    }

    // One to Many
    // To get the comments related to a post
    public function comments(){
        return $this->hasMany(Comment::class);
    }

}
