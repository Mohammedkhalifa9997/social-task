<?php

namespace App\Http\Controllers\Front\Comment;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Services\Front\Comment\CommentService;
use App\Http\Requests\Front\Comment\StoreCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private CommentService $commentService)
    {
    }

    public function store(StoreCommentRequest $request)
    {
        $result = $this->commentService->store($request);

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

    public function index(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $postId = (int) $request->post_id;

        $comments = $this->commentService->getComments($postId);

        return $this->apiResponse(
            ['comments' => $comments],
            'Comments retrieved successfully',
            [],
            200
        );
    }
}
