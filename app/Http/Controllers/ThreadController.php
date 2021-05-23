<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Http\Controllers\PostController;
use App\Http\Resources\PostResource;
use App\Http\Resources\ThreadResource;
use App\Http\Resources\ThreadCollection;
use App\Models\Post;
use Hashids;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $limit
     * @return ThreadCollection
     */
    public function index(int $limit = 10): ThreadCollection
    {
        $threads = Thread::with('posts')
            ->orderBy('created_at', 'desc')
            ->with('author')
            ->paginate($limit);
        return new ThreadCollection($threads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $req = $request->all();
        $validator = $this->validateThread($req);
        if (!\Auth::check()) {
            return [
                'resource' => 'thread',
                'action' => 'create',
                'status' => 'error',
                'errors' => ['Not logged in']
            ];
        }

        if ($validator->fails()) {
            return [
                'resource' => 'thread',
                'action' => 'create',
                'status' => 'error',
                'errors' => $errors = $validator->errors(),
            ];

        } else {
            $userID = \Auth::user()->id;
            $threadData = [
                'title' => $req['title'],
                'sticky' => 0,
                'user_id' => $userID
            ];
            $thread = new Thread;
            $thread->fill($threadData);
            $thread->save();

            $postData = [
                'user_id' => $userID,
                'post_id' => null,
                'title' => '',
                'body' => $req['body'],
                'thread_id' => $thread->id,
            ];

            $this->createPost($postData);
            return [
                'resource' => 'thread',
                'action' => 'create',
                'status' => 'success',
                'thread' => new ThreadResource($thread)
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $hash
     * @return ThreadResource
     */
    public function show($hash): ThreadResource
    {
        $thread = $this->getThreadByHash($hash);
        return (new ThreadResource($thread))->withPosts(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $hash
     * @param Request $request
     * @return array
     */
    public function update($hash, Request $request): array
    {
        if (!\Auth::check()) {
            return [
                'resource' => 'thread',
                'action' => 'update',
                'status' => 'error',
                'errors' => ['Not logged in']
            ];
        }
        $thread = $this->getThreadByHash($hash);
        $req = $request;
        $userID = \Auth::user()->id;

        $postData = [
            'user_id' => $userID,
            'thread_id' => $thread->id,
            'post_id' => null,
            'title' => $req['title'],
            'body' => $req['body'],
        ];

        $threadPost = $thread->posts->where('is_first',1);
        $post = new PostController;
        dd($post->updatePost($threadPost->hash,$postData));
        if ($req['action'] === 'answer') {
            $post = $this->createPost($postData);
        } else if ($req['action'] === 'update') {
            // $this->update($hash,$data);
            // PostController::updatePost($hash,$data);
        }
        dd($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Answer to a Thread
     *
     * @param $hash
     * @param Request $request
     * @return array
     */
    public function answer($hash, Request $request): array
    {
        if (!\Auth::check()) {
            return [
                'resource' => 'thread',
                'action' => 'answer',
                'status' => 'error',
                'errors' => ['Not logged in']
            ];
        }
        $thread = $this->getThreadByHash($hash);
        $req = $request;
        $userID = \Auth::user()->id;

        $postData = [
            'user_id' => $userID,
            'thread_id' => $thread->id,
            'post_id' => null,
            'title' => $req['title'],
            'body' => $req['body'],
        ];

        $post = new PostController;
        dd($post->createPost($postData));
    }

    /**
     * Thread Validator
     * @param array $data
     * @return mixed
     */
    protected function validateThread(array $data): mixed
    {
        return Validator::make($data, [
            'title' => 'required|min:5',
            'body' => 'required|min:40',
        ]);
    }

    /**
     * Get Thread-ID - Helper Function
     * @param $hash
     * @return null
     */
    private function getThreadByHash($hash)
    {
        $id = Hashids::connection('threads')->decode($hash);
        if (is_numeric($id[0])) {
            return Thread::findOrFail($id[0]);
        } else {
            return null;
        }
    }
}
