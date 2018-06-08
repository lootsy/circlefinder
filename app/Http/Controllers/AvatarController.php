<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function index()
    {
        return redirect(route('profile.avatar.edit'));
    }

    public function edit(Request $request)
    {
        $user = auth()->user();
        return view('profile.avatar.edit')->with(compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'avatar' => 'image'
        ]);

        $user = auth()->user();

        if($request->hasFile('avatar'))
        {
            $file = $request->file('avatar');

            $new_fileName = $user->newAvatarFileName();

            $request->avatar->storeAs('avatars', $new_fileName);

            $user->avatar = $new_fileName;

            $user->save();
        }
        else
        {
            return redirect()->back()->withErrors("You have to choose a file for upload");
        }

        return redirect()->route('profile.index')->with("success", "Your avatar was changed!");
    }

    public function download($file)
    {
        return Storage::download('avatars/' . $file);
    }
}
