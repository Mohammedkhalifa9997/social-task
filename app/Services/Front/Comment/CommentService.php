<?php

namespace App\Services\Front\Comment;

use Exception;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function store($request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id;

            $post = Post::findOrFail($data['post_id']);

            $comment = Comment::create($data);
            $post->increment('comments_count');

            $comment->load('user');

            return [
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => [
                    'comment' => [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'username' => $comment->user->username,
                            'image' => displayImage($comment->user->image),
                        ],
                    ],
                    'comments_count' => $post->fresh()->comments_count,
                ],
            ];
        } catch (Exception $e) {
            \Log::error('Comment store error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function getComments($postId)
    {
        $postId = (int) $postId;

        $comments = Comment::where('post_id', $postId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        return $comments->map(function ($comment) {
            if (!$comment->user) {
                return null;
            }

            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'username' => $comment->user->username,
                    'image' => displayImage($comment->user->image),
                ],
            ];
        })->filter()->values()->toArray();
    }
}
