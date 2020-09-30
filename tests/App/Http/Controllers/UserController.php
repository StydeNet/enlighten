<?php

namespace Tests\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tests\App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        return view('user.show', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required']);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect('/');
    }
}
