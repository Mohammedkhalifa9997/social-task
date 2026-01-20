<?php

namespace App\Http\Requests\Front\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:65535'],
            'images' => ['nullable', 'array', 'min:1', 'max:5'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp', 'max:5120'],
        ];
    }
}
