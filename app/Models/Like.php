<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pokemon_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasLikedPokemon($pokemon_id)
    {
        return $this->where('pokemon_id', $pokemon_id)
            ->where('user_id', auth()->id())
            ->first();
    }
}
