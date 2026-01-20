<?php

namespace App\Http\Controllers\Front\Like;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\Front\Like\LikeService;
use App\Http\Requests\Front\Like\ToggleLikeRequest;

class LikeController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private LikeService $likeService)
    {
    }

    public function toggleLike(ToggleLikeRequest $request)
    {
        $result = $this->likeService->toggleLike($request);

        if ($result['success']) {
            return $this->apiResponse(
                $result['data'],
                $result['message'],
                [],
                200
            );
        } else {
            return $this->apiResponse(
                $result['data'],
                $result['message'],
                [],
                400
            );
        }
    }

    public function getLikedUsers(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $likedUsers = $this->likeService->getLikedUsers($request->post_id);

        return $this->apiResponse(
            ['users' => $likedUsers],
            'Liked users retrieved successfully',
            [],
            200
        );
    }
}
