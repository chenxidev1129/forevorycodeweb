<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('profile_id');
            $table->integer('user_id');
            $table->enum('type', ['image', 'video', 'audio']);
            $table->string('media');
            $table->string('duration')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('caption')->nullable();
            $table->integer('position')->default('0');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_media');
    }
}
