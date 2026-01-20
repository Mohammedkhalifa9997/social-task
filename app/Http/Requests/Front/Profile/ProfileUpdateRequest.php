<?php

namespace App\Http\Requests\Front\Profile;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+(?:-[a-zA-Z0-9]+)*$/', Rule::unique(User::class)->ignore($this->user()->id)],
            'password' => ['nullable', 'string', Password::defaults()],
            'bio' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp', 'max:5120'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
