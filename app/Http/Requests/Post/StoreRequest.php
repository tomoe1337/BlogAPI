<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'image' => 'nullable|string|max:255',
            'category' => 'nullable|array',
            'category.id' => 'nullable|integer|exists:categories,id',
            'category.title' => 'nullable|string|min:3|max:255',
            'tags' => 'nullable|array',
            'tags.*.id' => 'nullable|integer|exists:tags,id',
            'tags.*.title' => 'nullable|string|min:2|max:255',
        ];
    }
}
