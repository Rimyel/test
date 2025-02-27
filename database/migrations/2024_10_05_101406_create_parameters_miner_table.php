<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parameter_miner', function (Blueprint $table) {
            $table->id();

            // Связь с таблицей miners (posters)
            $table->unsignedBigInteger('miner_id');
            $table->foreign('miner_id')
                ->references('id')
                ->on('posters') 
                ->onDelete('cascade');

            // Связь с таблицей parameters
            $table->unsignedBigInteger('parameter_id');
            $table->foreign('parameter_id')
                ->references('id')
                ->on('parameters')
                ->onDelete('cascade');

            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('parameter_miner');
    }
};