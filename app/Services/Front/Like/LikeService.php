<?php

namespace App\Services\Front\Like;

use Exception;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class LikeService
{
    public function toggleLike($request)
    {
        $data = $request->validated();
        $postId = $data['post_id'];
        DB::beginTransaction();
        try {
            $post = Post::findOrFail($postId);
            $userId = auth()->user()->id;

            $like = Like::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if ($like) {
                $like->delete();
                if ($post->likes_count > 0) {
                    $post->decrement('likes_count');
                }
                $isLiked = false;
                $message = 'Like removed successfully';
            } else {
                $like = Like::firstOrCreate(
                    [
                        'post_id' => $post->id,
                        'user_id' => $userId,
                    ]
                );

                if ($like->wasRecentlyCreated) {
                    $post->increment('likes_count');
                }

                $isLiked = true;
                $message = 'Like added successfully';
            }

            $post->refresh();

            DB::commit();
            return [
                'success' => true,
                'message' => $message,
                'data' => [
                    'likes_count' => $post->likes_count,
                    'is_liked' => $isLiked,
                ],
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            if ($e->getCode() == 23000) {
                try {
                    $post = Post::findOrFail($postId);
                    $userId = auth()->user()->id;

                    $like = Like::where('post_id', $post->id)
                        ->where('user_id', $userId)
                        ->first();

                    $post->refresh();

                    return [
                        'success' => true,
                        'message' => 'Like added successfully',
                        'data' => [
                            'likes_count' => $post->likes_count,
                            'is_liked' => $like ? true : false,
                        ],
                    ];
                } catch (Exception $retryException) {
                    return [
                        'success' => false,
                        'message' => 'Failed to toggle like: ' . $retryException->getMessage(),
                        'data' => [],
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to toggle like: ' . $e->getMessage(),
                'data' => [],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to toggle like: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function getLikedUsers($postId)
    {
        $currentUser = auth()->user();

        $likes = Like::where('post_id', $postId)
            ->with('user')
            ->get();

        $likedUsers = $likes->map(function ($like) use ($currentUser) {
            $user = $like->user;
            $connectionStatus = $currentUser->getConnectionStatus($user);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'image' => displayImage($user->image),
                'is_friend' => $currentUser->isFriendWith($user),
                'is_current_user' => $user->id === $currentUser->id,
                'connection_status' => $connectionStatus?->value,
            ];
        })->unique('id')->values();

        return $likedUsers;
    }
}
