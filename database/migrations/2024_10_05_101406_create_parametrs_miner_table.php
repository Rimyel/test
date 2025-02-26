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
    Schema::create('parametr_miner', function (Blueprint $table) {
        $table->id();
        $table->foreignId('parametr_id')->constrained()->onDelete('cascade');
        $table->foreignId('miner_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('parametr_miner');
}
};
