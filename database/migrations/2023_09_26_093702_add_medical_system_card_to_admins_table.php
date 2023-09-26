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
        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('medical_system_card_id')->nullable()->after('medical_system_number');
            $table->foreign('medical_system_card_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['medical_system_card_id']);
            $table->dropColumn('medical_system_card_id');
        });
    }
};
