<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Убедись что значение поля image_id существует в столбце id таблицы post_images
     * id пользователь должен совпадать с id авторизованного пользователя
     * status должен быть false
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_id' => ['nullable', Rule::exists('post_images', 'id')
                ->where(fn($query) => $query->where('user_id', auth()->id())
                    ->where('status', false))],
        ];
    }
}
