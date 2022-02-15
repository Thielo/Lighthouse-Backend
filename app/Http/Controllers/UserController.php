<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Response;
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
        $users = UserCollection::make(User::withTrashed()->get())->complete(true);
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
        $user = $this->getUserCollectionByHash($hash);
        $userData = UserCollection::make($user)->complete(true);
        return $userData;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $hash)
    {
        $user = $this->getUserByHash($hash);
        $user->username = $request->input('username');
        $user->save();
        return Response::json(
            array(
                'status' => 'success',
                'action' => 'update',
                'user' => $user
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($hash): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByHash($hash);
        $user->delete();
        return Response::json(
            array(
                'status' => 'success',
                'action' => 'delete',
                'user' => $user
            )
        );
    }

    /**
     * Force-Removes the specified resource from storage.
     *
     * @param $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($hash): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByHash($hash);
        $user->forceDelete();
        return Response::json(
            array(
                'status' => 'success',
                'action' => 'force-delete',
                'user' => $user
            )
        );
    }

    /**
     * Restores the specified resource from storage.
     *
     * @param $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($hash): \Illuminate\Http\JsonResponse
    {
        $user = $this->getUserByHash($hash);
        $user->restore();
        return Response::json(
            array(
                'status' => 'success',
                'action' => 'force-delete',
                'user' => $user
            )
        );
    }

    /**
     * @param $hash
     * @return User
     */
    public function getUserByHash ($hash): User
    {
        $id = Hashids::connection('users')->decode($hash);
        return User::where('id', $id)->firstOrFail();
    }

    /**
     * @param $hash
     * @return User
     */
    public function getUserCollectionByHash ($hash): User
    {
        $id = Hashids::connection('users')->decode($hash);
        return User::findOrFail($id);
    }
}
