<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->id),
            ],
            'password' => 'nullable|string|min:8|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date_format:Y-m-d'
        ]);

        if (isset($request->password)) {
            $user = User::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'birthday' => $validatedData['birthday']
                ]
            );
        } else {
            $user = User::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $validatedData['email'],
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'birthday' => $validatedData['birthday']
                ]
            );
        }


        $user->save();

        return response()->json(['user' => $user], 201);
    }

    public function uploadAvatar(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = $request->file('avatar')->storePublicly('avatars', 'public');

        $user->avatar = env('APP_URL') . '/storage' .'/'. $path;
        $user->save();

        return response()->json(['user' => $user], 200);
    }
}
