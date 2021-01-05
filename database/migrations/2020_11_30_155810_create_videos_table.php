<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title')->nullable();
            $table->string('description', 10000)->nullable();
            $table->string('videoID');
            $table->string('uploadUrl');
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('listed')->default(0);
            $table->unsignedBigInteger('sizeKB')->default(0);
            $table->timestamps();
            $table->string('status')->default('uploading');

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
