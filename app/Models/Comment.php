<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\DescriptionList\Parser\DescriptionTermContinueParser;

class Comment extends Model
{
    // One to Many (Inverse)
    // To get the owner of the comment
    public function user(){
        return $this->belongsTo(User::class);
    }
}