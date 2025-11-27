<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    // Insert a comment to comments rable
    public function store(Request $request, $post_id){
        // 1. Valodate the request
        $request->validate([
            'comment' => 'required|max:150'
        ]);

        // 2. Save the form data to comments table
        $this->comment->user_id     = Auth::user()->id;
        $this->comment->post_id     = $post_id;
        $this->comment->body        = $request->comment;
        $this->comment->save();

        return redirect()->back();
    }

    // Deletes a comment of a post
    public function destroy($id){
        $this->comment->destroy($id);
        return redirect()->back();
    }

    public function update(Request $request, $id){
         $request->validate([
            'comment' => 'required|max:150'
        ]);

        $comment = Comment::findOrFail($id);

        $comment->body = $request->comment;
        $comment->save();

        return redirect()->back();
    }

    
}



