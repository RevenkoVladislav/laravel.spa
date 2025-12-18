<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Получить всех пользователей, кроме самого себя
     */
    public function index()
    {
        $users = User::whereNot('id', auth()->id())->get();
        return UserResource::collection($users);
    }

    /**
     * Подгружаем пользователей
     * Цепляем image для N+1
     */
    public function show(User $user)
    {
        $posts = $user->posts()
            ->with('image')
            ->latest()
            ->get();

        return PostResource::collection($posts);
    }
}
