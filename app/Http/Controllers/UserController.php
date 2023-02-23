<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['likes', 'dislikes'])->paginate(20);
        return response()->json(['data' => $users]);
    }

    public function getCurrentUser(Request $request)
    {
        $user = $request->user();

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'birthday' => $user->birthday,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'likes' => $user->likes,
            'dislikes' => $user->dislikes
        ];

        return response()->json($userData);
    }
}
