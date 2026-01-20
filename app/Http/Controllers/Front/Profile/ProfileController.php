<?php

namespace App\Http\Controllers\Front\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Profile\ProfileUpdateRequest;
use App\Traits\ImageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ImageTrait;

    public function index(Request $request)
    {
        $posts = auth()->user()->posts()->with('images')->latest()->paginate(20);

        if ($request->ajax()) {
            $postImagesData = [];
            foreach ($posts as $post) {
                if ($post->images->count() > 1) {
                    $postImagesData[$post->id] = $post->images->map(function ($image) {
                        return displayImage($image->image);
                    })->toArray();
                }
            }

            return response()->json([
                'html' => view('front.pages.profile.partials.posts', ['posts' => $posts])->render(),
                'pagination' => view('front.pages.profile.partials.pagination', ['posts' => $posts])->render(),
                'postImagesData' => $postImagesData,
            ]);
        }

        $user = auth()->user();
        $friendsCount = $user->friends()->count();

        return view('front.pages.profile.index', [
            'posts' => $posts,
            'postsCount' => $posts->total(),
            'friendsCount' => $friendsCount,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        return view('front.pages.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['image'] = $this->updateImage(auth()->user()->image, 'users', 'image');

        if (!isset($data['password'])) {
            unset($data['password']);
        }

        $request->user()->fill($data);

        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('Success', 'Profile updated successfully');
    }
}
