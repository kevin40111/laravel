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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->string('billing');
            $table->string('fullName');
            $table->string('company');
            $table->string('role');
            $table->string('username');
            $table->string('country');
            $table->string('contact');
            $table->string('email');
            $table->string('currentPlan');
            $table->string('status');
            $table->string('avatar')->nullable();
            $table->string('avatarColor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profile', function (Blueprint $table) {
            //
        });
    }
};

