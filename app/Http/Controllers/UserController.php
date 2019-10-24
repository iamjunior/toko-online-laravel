<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\User::paginate(10);
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view("users.create");
    }

    public function store(Request $request)
    {
        $new_user           = new \App\User;
        $new_user->name     = $request->get('name');
        $new_user->username = $request->get('username');
        $new_user->roles    = json_encode($request->get('roles'));
        $new_user->address  = $request->get('address');
        $new_user->phone    = $request->get('phone');
        $new_user->email    = $request->get('email');
        $new_user->password = \Hash::make($request->get('password'));

        if($request->file('avatar')){
            $file = $request->file('avatar')->store('avatars','public');
            $new_user->avatar = $file;
        }

        $new_user->save();
        return redirect()->route('users.create')->with('status','User sucessfully created');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = \App\User::findOrFail($id);
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user           = \App\User::findOrFail($id);
        $user->name     = $request->get('name');
        $user->roles    = json_encode($request->get('roles'));
        $user->address  = $request->get('address');
        $user->phone    = $request->get('phone');
        $user->status   = $request->get('status');
        if($request->file('avatar')){
            if($user->avatar && file_exists(storage_path('app/public/' . $user->avatar)))
            {
                \Storage::delete('public/'.$user->avatar);
            }
            $file = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $file;
        }

        $user->save();
        return redirect()->route('users.edit', [$id])->with('status', 'User succesfully updated');
    }

    public function destroy($id)
    {
        //
    }
}
