<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\returnSelf;

class PostController extends Controller
{
    private $post;
    const LOCAL_STORAGE_FOLDER = 'images/';  // folder path where the images will be atored

    public function __construct(Post $post){
        $this->post = $post;
    }

    // Opens index/landing page
    public function index(){
        // return view('posts.index');

        $all_posts = Post::orderBy('created_at', 'desc')->paginate(3);
        // SELECT * FROM posts ORDER BY DESC;
        return view('posts.index')->with('all_posts', $all_posts);
    }

    // Opens create page
    public function create(){
        return view('posts.create');
    }

    // Insert post in posts table
    public function store(Request $request){ // $_POST[]
        // 1. Validate the reqest　　　　　　from the form
        $request->validate([ // validasion rules
            'title' => 'required|max:50',
            'body' => 'required|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048'
                                // Multipurpose Internet Mail Extensions
        ]);

        // 2. Save the form data to posts table
        $this->post->user_id  = Auth::user()->id; // Auth::user() contains the info of the logged in user
        $this->post->title    = $request->title;
        $this->post->body     = $request->body;
        $this->post->image    = $this->saveImage($request->image);
        $this->post->save();

        // 3. Redirect to homepage
        return redirect()->route('index');
    }

    private function saveImage($image){
        // 1. Change the name of the image to CURRENT TIME to avoid overwriting
        $image_name = time() . "." . $image->extension();
        // time() return the UNIX timestamp and calculates the number of secounds since Jan 1 1970

        // 2. Save the image to storage/app/public/images
        // $image->storeAs(storage/app/public/images/, $image_name)
        $image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);
        // storeAs() is used to store a file in a specified directory with a custom name

        return $image_name;
    }

    // Opens show page and display the post details of a specific post
    public function show($id){
        $post = $this->post->findOrFail($id);
        // SELECT * FROM posts WHERE id = $id;
        return view('posts.show')->with('post', $post);
    }

    // Opens edit page and display the post details of a specific post
    public function edit($id){
        $post = $this->post->findOrFail($id);

        // Authorization check
        // prevent users from attempting to edit posts they do not own
        if($post->user->id != Auth::user()->id){
            return redirect()->back();
        }

        return view('posts.edit')->with('post', $post);
    }

    // Update a specific post
    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|max:50',
            'body' => 'required|max:1000',
            'image' => 'mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        $post         = $this->post->findOrFail($id);
        $post->title  = $request->title;
        $post->body   = $request->body;

        // IF there is a new image
        if($request->image){   //imege  file from the form
            // 1. Delete the previous/old image from the local storage
            $this->deleteImage($post->image);  //imege name from the model  1755566998.jpg

            // 2. Save the new image
            $post->image = $this->saveImage($request->image);
        }

        $post->save();
        return redirect()->route('post.show', $id);
    }

    // Delete the previous/old image from the local storage
    private function deleteImage($image){  //from the post
        $image_path = self::LOCAL_STORAGE_FOLDER .$image;  // Location of the old image
        // sample: $image_psth = 'images/17123456789.jpg';

        // Storage class works with files on different disks
        // disk('public') is equal to storage/app/public
        if(Storage::disk('public')->exists($image_path)){  // storage/app/public/images/17123456789.jpg
            Storage::disk('public')->delete($image_path);
        }
    }

    // Deletes a post
    public function destroy($id){
        $post = $this->post->findOrFail($id); // get the data specific from post
        $this->deleteImage($post->image);  // image name inside model/db
        $post->delete();

        return redirect()->back();
    }


}
