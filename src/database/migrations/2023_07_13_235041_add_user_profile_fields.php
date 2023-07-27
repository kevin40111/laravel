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
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("email_verified_at");
            $table->dropColumn("remember_token");

            $table->after("password", function ($table) {
                $table->string("status")->default("active");
                $table->string("billing")->nullable();
                $table->string("avatar")->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->timestamp("email_verified_at")->nullable();
            $table->rememberToken();

            $table->dropColumn("billing");
            $table->dropColumn("status");
            $table->dropColumn("avatar");
        });
    }
};
