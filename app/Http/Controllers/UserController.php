<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserCollection;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index()
    {
        $users = UserCollection::make(User::withTrashed()->all())->complete(true);
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function show($hash)
    {
        $user = $this->getUserById($hash);
        $userData = UserCollection::make($user)->complete(true);
        return $userData;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $hash)
    {
        $id = Hashids::connection('users')->decode($hash);
        $user = User::firstOrFail('id', $id);
        $user->username = $request->input('username');
        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserById ($hash) {
        $id = Hashids::connection('users')->decode($hash);
        $user = User::findOrFail($id);
        return $user;
    }
}
