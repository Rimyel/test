<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('genre_poster', function (Blueprint $table) {
        $table->id();
        $table->foreignId('genre_id')->constrained()->onDelete('cascade');
        $table->foreignId('poster_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('genre_poster');
}
};
