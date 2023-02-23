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
}
