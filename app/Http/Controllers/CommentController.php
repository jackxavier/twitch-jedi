<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewComment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @param Post $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Post $post)
    {
        return response()->json($post->comments()->with('user')->latest()->get());
    }

    /**
     * @param Request $request
     * @param Post    $post
     *
     * @return mixed
     */
    public function store(Request $request, Post $post)
    {
        $comment = $post->comments()->create(
            [
                'body'    => $request->body,
                'user_id' => Auth::id(),
            ]
        );

        $comment = Comment::where('id', $comment->id)->with('user')->first();

        event(new NewComment($comment));

        return $comment->toJson();
    }
}
