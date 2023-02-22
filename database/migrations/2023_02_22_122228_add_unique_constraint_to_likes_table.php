<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->unique(['user_id', 'pokemon_id']);
        });
    }

    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'pokemon_id']);
        });
    }
};