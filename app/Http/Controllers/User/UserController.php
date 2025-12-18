<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

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
}
