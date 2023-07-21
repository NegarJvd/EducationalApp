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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cluster_id');
            $table->tinyInteger('number');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('cover_id')->nullable();
            $table->unsignedBigInteger('video_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cluster_id')->references('id')->on('clusters');
            $table->foreign('cover_id')->references('id')->on('files');
            $table->foreign('video_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
