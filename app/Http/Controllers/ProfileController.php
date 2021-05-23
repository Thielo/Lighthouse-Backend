<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hashids;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param $hash
     * @param Request $request
     * @return ProfileResource|\Illuminate\Http\JsonResponse
     */
    public function show($hash, Request $request): ProfileResource|\Illuminate\Http\JsonResponse
    {
        if ($hash === 'me') {
            if (!is_null($request->user())) {
                $profile = $request->user();
                return (new ProfileResource($profile))->complete(true)->private(true);

            } else {
                return response()->json(false);
            }
        } else {
            $profile = $this->getProfileByHash($hash);
            return (new ProfileResource($profile))->complete(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Get Thread-ID - Helper Function
     * @param string $hash
     * @return ?User
     */
    public function getProfileByHash($hash): ?User
    {
        $id = Hashids::connection('users')->decode($hash);
        if (is_numeric($id[0])) {
            return User::findOrFail($id[0]);
        } else {
            return null;
        }
    }
}
