<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("user_profile", function (Blueprint $table) {
            $table
                ->string("billing")
                ->default("")
                ->change();
            $table
                ->string("fullName")
                ->default("")
                ->change();
            $table
                ->string("company")
                ->default("")
                ->change();
            $table
                ->string("role")
                ->default("client")
                ->change();
            $table
                ->string("username")
                ->default("")
                ->change();
            $table
                ->string("country")
                ->default("")
                ->change();
            $table
                ->string("contact")
                ->default("")
                ->change();
            $table
                ->string("email")
                ->default("")
                ->change();
            $table
                ->string("currentPlan")
                ->default("")
                ->change();
            $table
                ->string("status")
                ->default("active")
                ->change();
            $table->string("avatar")->nullable();
            $table
                ->string("avatarColor")
                ->default("")
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("user_profile", function (Blueprint $table) {
            //
        });
    }
};
