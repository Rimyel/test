<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poster_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // Можно использовать IP-адрес или идентификатор пользователя
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('views');
    }
};
