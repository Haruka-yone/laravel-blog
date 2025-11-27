<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $user;
    const LOCAL_STORAGE_FOLDER = 'avatars/'; // folder path where the avatars will be stored

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Open the profile page
    public function show(){
        return view('users.show')->with('user', Auth::user());
    }

    public function specificshow($id){
        $user = $this->user->findOrFail($id);
        return view('users.show')->with('user', $user);
    }

    // Open edit page
    public function edit(){
        return view('users.edit')->with('user', Auth::user());
    }

    // Update the user
    public function update(Request $request){
        // Validate the request
        $request->validate([
            'avatar' => 'mimes:jpeg,jpg,png,gif|max:1048',
            'name'   => 'required|max:50',
            'email'  => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
                                            //  check all emails inside users table except/skip this ID
            'password' => 'required|min:8'

        ]);
        // Insert new data: unique:table,column / unique:table
        // Update new data: unique:table,column,exceptID

        $user         = $this->user->findOrFail(Auth::user()->id);
        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->password = $request->password;

         // IF there is a new image
        if($request->avatar){   //imege  file from the form
            // 1. If the user aurrently has an avatar, delete it first from local storage
            $this->deleteAvatar($user->avatar);

            // 2. Save the new avatar
            $user->avatar = $this->saveAvatar($request->avatar);
        }

        $user->save();

        return redirect()->route('profile.show');
    }

    private function saveAvatar($avatar){
        // 1. Change the name of the avatar to CURRENT TIME to avoid overwriting
        $avatar_name = time() . "." . $avatar->extension();
        // time() return the UNIX timestamp and calculates the number of secounds since Jan 1 1970

        // 2. Save the image to storage/app/public/avatar
        $avatar->storeAs(self::LOCAL_STORAGE_FOLDER, $avatar_name);

        return $avatar_name;
    }

     // Delete the previous/old image from the local storage
    private function deleteAvatar($avatar){  //from the post
        $avatar_path = self::LOCAL_STORAGE_FOLDER .$avatar;  // Location of the old image

        if(Storage::disk('public')->exists($avatar_path)){  // storage/app/public/images/17123456789.jpg
            Storage::disk('public')->delete($avatar_path);
        }
    }

}
