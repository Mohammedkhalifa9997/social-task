<?php

namespace App\Services\Front\Post;

use Exception;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Connection;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use App\Enums\ConnectionStatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostService
{
    use ImageTrait;

    public function index()
    {
        $user = auth()->user();

        $sentFriendIds = Connection::where('sender_id', $user->id)
            ->where('status', ConnectionStatusEnum::ACCEPTED->value)
            ->pluck('receiver_id');

        $receivedFriendIds = Connection::where('receiver_id', $user->id)
            ->where('status', ConnectionStatusEnum::ACCEPTED->value)
            ->pluck('sender_id');

        $friendIds = $sentFriendIds->merge($receivedFriendIds)->unique()->toArray();

        $query = Post::with(['user', 'images']);

        if (!empty($friendIds)) {
            $placeholders = implode(',', array_fill(0, count($friendIds), '?'));
            $query->orderByRaw("CASE WHEN user_id IN ({$placeholders}) THEN 0 ELSE 1 END", $friendIds);
        }

        $posts = $query->orderBy('id', 'desc')->paginate(10);

        return $posts;
    }

    public function store($request)
    {
        $uploadedImages = [];
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id;

            $images = $data['images'] ?? [];
            unset($data['images']);

            if (!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = $this->uploadImage($image, 'posts');
                    $uploadedImages[] = $imagePath;
                }
            }

            $post = Post::create($data);

            if (!empty($uploadedImages)) {
                foreach ($uploadedImages as $imagePath) {
                    PostImage::create([
                        'post_id' => $post->id,
                        'image' => $imagePath,
                    ]);
                }
            }

            DB::commit();
            return $post;

        } catch (Exception $e) {
            $this->deleteUploadedImages($uploadedImages);
            DB::rollBack();
            return false;
        }
    }

    private function deleteUploadedImages(array $imagePaths)
    {
        foreach ($imagePaths as $imagePath) {
            try {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return true;
    }

    public function update($request, $post)
    {
        $uploadedImages = [];
        $imagesToDelete = [];
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $images = $data['images'] ?? [];
            $existingImages = $data['existing_images'] ?? [];
            unset($data['images']);
            unset($data['existing_images']);

            $currentImages = $post->images;

            if ($request->has('existing_images')) {
                foreach ($currentImages as $currentImage) {
                    if (!in_array($currentImage->id, $existingImages)) {
                        $imagesToDelete[] = $currentImage;
                    }
                }

                if (!empty($imagesToDelete)) {
                    $this->deleteFiles($imagesToDelete, 'image');
                }
            }

            if (!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = $this->uploadImage($image, 'posts');
                    $uploadedImages[] = $imagePath;
                }
            }

            $post->update($data);

            if (!empty($uploadedImages)) {
                foreach ($uploadedImages as $imagePath) {
                    PostImage::create([
                        'post_id' => $post->id,
                        'image' => $imagePath,
                    ]);
                }
            }

            DB::commit();
            return $post;

        } catch (Exception $e) {
            $this->deleteUploadedImages($uploadedImages);
            DB::rollBack();
            return false;
        }
    }

    public function destroy($post)
    {
        if (auth()->id() !== $post->user_id) {
            return false;
        }
        DB::beginTransaction();
        try {
            $this->deleteFiles($post->images, 'image');
            $post->images()->delete();
            $post->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
