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

    public function store($pokemon_id)
    {
        $user = auth()->user();
        $like = new Like([
            'user_id' => $user->id,
            'pokemon_id' => $pokemon_id,
        ]);
        $like->save();

        return response()->json(['id' => $like->id]);
    }

    public function destroy(Like $like)
    {
        $like->delete();

        return response()->json(['like' => false]);
    }
}
