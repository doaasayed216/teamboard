<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = User::find(auth()->id());
        $attributes = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user)],
            'avatar' => ['image']
        ]);

        if($request->hasFile('avatar')) {
            $attributes['avatar'] = $request->file('avatar')->store('avatars');
        }

        $user->update($attributes);
        return back();
    }
}
