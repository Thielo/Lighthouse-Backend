<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\ThreadResource;
use App\Http\Resources\ThreadCollection;
use App\Models\Post;
use Redirect, Validator;
use App\Models\Thread;
use Hashids;

class PostController extends Controller
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
     * @param $data
     * @return array
     */
    public function create($data)
    {
        if ($data['title'] == null) {
            $data['title'] = '';
        }

        $validator = $this->validatePost($data);
        if ($validator->fails()) {

            return [
                'resource' => 'post',
                'action' => 'create',
                'status' => 'error',
                'errors' => [
                    $errors = $validator->errors()
                ]
            ];

        } else {

            $post = new Post;
            $post->fill($data);
            $post->save();

            return [
                'resource' => 'post',
                'action' => 'create',
                'status' => 'success',
                'data' => (new PostResource($post))->withThread(false)
            ];

        }
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
     * @return PostResource
     */
    public function show($hash)
    {
        $post = $this->getPostByHash($hash);
        return (new PostResource($post))->withThread(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $hash
     * @param $data
     * @return array
     */
    public function edit($hash, $data): array
    {
        if ($data['title'] == null) {
            $data['title'] = '';
        }

        $validator = $this->validatePost($data);
        if ($validator->fails()) {

            return [
                'resource' => 'post',
                'action' => 'update',
                'status' => 'error',
                'errors' => [
                    $errors = $validator->errors()
                ]
            ];

        } else {

            $post = $this->getPostByHash($hash);
            $post->update($data);
            $post->save();

            return [
                'resource' => 'post',
                'action' => 'update',
                'status' => 'success',
                'data' => (new PostResource($post))->withThread(false),
            ];

        }
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
     * Post Validator
     * @param array $data
     * @return mixed
     */
    protected function validatePost(array $data)
    {
        return Validator::make($data, [
            'body' => 'required|min:40',
        ]);
    }

    /**
     * Get Post-ID
     * @param string $hash
     * @return Post|null
     */
    protected function getPostByHash($hash): ?Post
    {
        $id = Hashids::connection('posts')->decode($hash);
        if (is_numeric($id[0])) {
            return Post::with('thread')->with('author')->findOrFail($id[0]);
        } else {
            return null;
        }
    }
}
