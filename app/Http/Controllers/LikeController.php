<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index($pokemon_id)
    {
        $likes = Like::where('pokemon_id', $pokemon_id)->get();
        return response()->json($likes);
    }

    public function UserLikes()
    {
        $likes = Like::where("user_id", auth()->user()->id)->where("type", 1)->get();
        $dislikes = Like::where("user_id", auth()->user()->id)->where("type", 0)->get();

        return response()->json([
            "likes" => $likes,
            "dislikes" => $dislikes,
        ]);
    }

    public function PokemonLikesCount($id)
    {
        $count = Like::where('pokemon_id', $id)->count();
        $like = (new Like)->hasLikedPokemon($id);
        return response()->json(['count' => $count, 'like' => $like]);
    }

    public function find($pokemon_id)
    {
        $like = Like::where('pokemon_id', $pokemon_id)
            ->where('user_id', Auth::id())
            ->first();
        return response()->json(['like' => $like]);
    }

    public function store(Request $request)
    {
        // Count the number of likes and dislikes for the current user
        $userLikes = Like::where('user_id', auth()->id())->where('type', 1)->count();
        $userDislikes = Like::where('user_id', auth()->id())->where('type', 0)->count();

        // Return an error response if the user has already liked 3 Pokemon or disliked 3 Pokemon
        if ($request->type === 1 && $userLikes >= 3) {
            return response()->json(['message' => 'You have already liked 3 Pokemon.'], 403);
        } elseif ($request->type === 0 && $userDislikes >= 3) {
            return response()->json(['message' => 'You have already disliked 3 Pokemon.'], 403);
        }

        // Create a new like
        $like = Like::updateOrCreate(
            ['user_id' => auth()->id(), 'pokemon_id' => $request->pokemon_id],
            ['type' => $request->type]
        );

        return response()->json(['like_id' => $like->id]);
    }


    public function destroy(Like $like)
    {
        $like->delete();

        return response()->json(['like' => false]);
    }
}
