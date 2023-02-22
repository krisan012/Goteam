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

    public function UserLikes(Request $request)
    {
        $user = auth()->user();
        $pokemonIds = explode(',', $request->input('pokemon_ids'));
        $likes = Like::where('user_id', $user->id)
            ->whereIn('pokemon_id', $pokemonIds)
            ->get();

        return response()->json($likes);
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
        // Check if the user has already liked 3 Pokemon
        $userLikeCount = Like::where('user_id', auth()->id())->count();
        if ($userLikeCount >= 3) {
            return response()->json(['message' => 'You have already liked 3 Pokemon.'], 403);
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
