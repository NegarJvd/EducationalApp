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
        Schema::create('content_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('content_id');
            $table->unsignedBigInteger('cluster_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('content_id')->references('id')->on('contents');
            $table->foreign('cluster_id')->references('id')->on('clusters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_user');
    }
};
