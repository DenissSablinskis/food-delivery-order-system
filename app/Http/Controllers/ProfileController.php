<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{

    public function show() {

        $user = auth()->user();
        return view('pages.user.profile', compact('user'));  

    }

    public function edit() {

        return view('pages.user.edit');
        
    }

    public function update(UpdateProfileRequest $request) {

        $user = auth()->user();
        $credentials = $request->validated();

        $user->update([
            'username' => $credentials['username'],
            'email' => $credentials['email'],
            'address' => $credentials['address'],
        ]);

        return redirect(route('profile.show'))->with('success', 'Profils veiksmīgi atjaunināts!');

    }

}
