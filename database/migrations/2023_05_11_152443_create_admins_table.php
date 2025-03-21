<?php

use App\Models\Admin;
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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->enum('status', Admin::status())->default(Admin::status()[0]);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('medical_system_number')->unique();
            $table->date('birth_date')->nullable();
            $table->enum('gender', Admin::gender())->nullable();
            $table->string('address')->nullable();
            $table->string('landline_phone')->nullable();
            $table->string('password')->nullable();
            $table->string('field_of_profession')->nullable();
            $table->text('resume')->nullable();
            $table->string('degree_of_education')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
