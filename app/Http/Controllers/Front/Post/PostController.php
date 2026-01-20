<?php

namespace App\Http\Controllers\Front\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Front\Post\PostService;
use App\Http\Requests\Front\Post\StorePostRequest;
use App\Http\Requests\Front\Post\UpdatePostRequest;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    public function index(Request $request)
    {
        $posts = $this->postService->index();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'posts' => $posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'content' => $post->content,
                        'created_at' => $post->created_at->diffForHumans(),
                        'user' => [
                            'id' => $post->user->id,
                            'name' => $post->user->name,
                            'username' => $post->user->username,
                            'profile_image' => displayImage($post->user->image),
                        ],
                        'images' => $post->images->map(function ($image) {
                            return displayImage($image->image);
                        })->toArray(),
                        'likes_count' => $post->likes_count,
                        'comments_count' => $post->comments_count,
                        'is_liked' => $post->isLikedBy(auth()->user()),
                    ];
                }),
                'currentPage' => $posts->currentPage(),
                'hasMore' => $posts->hasMorePages(),
            ]);
        }

        return view('front.pages.home.index', compact('posts'));
    }

    public function store(StorePostRequest $request)
    {
        $post = $this->postService->store($request);
        if ($post) {
            return back()
                ->with('Success', 'Post created successfully');
        }
        return back()
            ->with('Error', 'Post creation failed');
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post = $this->postService->update($request, $post);
        if ($post) {
            return back()
                ->with('Success', 'Post updated successfully');
        }
        return back()
            ->with('Error', 'Post update failed');
    }

    public function destroy(Post $post)
    {
        $result = $this->postService->destroy($post);
        if ($result) {
            return back()
                ->with('Success', 'Post deleted successfully');
        }
        return back()
            ->with('Error', 'Post deletion failed');
    }
}
