<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function index()
    {
        return $this->show(auth()->user()->uuid);
    }

    public function show($uuid)
    {
        $user = \App\User::withUuid($uuid)->firstOrFail();
        
        return view('profile.show')->with([
            'item' => $user
        ]);
    }

    public function edit()
    {
        $user = auth()->user();
        
        return view('profile.edit')->with([
            'item' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validationRules = \App\User::validationRules(['roles']);
        $validationRules['email'] .= ','.$user->id;

        $this->validate($request, $validationRules);

        $user->update($request->all());

        return redirect()->route('profile.index')->with([
            'success' => 'Your profile was updated!'
        ]);
    }

    public function passwordEdit(Request $request)
    {
        return view('profile.password.edit');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!(Hash::check($request->get('current_password'), $user->password)))
        {
            return redirect()->back()->withErrors("Provided password does not match with your current password");
        }

        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile.index')->with("success", "Your password was changed!");
    }
}
