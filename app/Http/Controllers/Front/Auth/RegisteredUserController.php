<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use ImageTrait;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('front.pages.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class, 'regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'bio' => ['sometimes', 'nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ]);

        $username = createSlug($request->name);

        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . '-' . time() . '-' . $counter;
            $counter++;
        }

        $image = $this->uploadImage($request->image, 'users');

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'password' => $request->password,
            'bio' => $request->bio,
            'image' => $image,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('verification.notice'))
            ->with('Success', 'Registration successful! Please verify your email address.');
    }
}
