<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // Тип события: page_view, link_click, time_on_site
            $table->text('url')->nullable(); // URL страницы
            $table->unsignedBigInteger('user_id')->nullable(); // ID пользователя (если есть)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics');
    }
}