<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("role", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });

        DB::table("role")->insert([
            [
                "name" => "client",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "admin",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);

        Schema::table("users", function (Blueprint $table): void {
            $table
                ->unsignedBigInteger("role_id")
                ->nullable()
                ->after("status");
            $table
                ->foreign("role_id", "users_role_id_foreign")
                ->references("id")
                ->on("role")
                ->nullOnDelete();
        });

        DB::table("users")
            ->where("role", "client")
            ->update(["role_id" => 1]);
        DB::table("users")
            ->where("role", "admin")
            ->update(["role_id" => 2]);

        Schema::table("users", function (Blueprint $table): void {
            $table->dropColumn("role");
            $table
                ->unsignedBigInteger("role_id")
                ->default(1)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table): void {
            $table->string("role")->default("client");
        });

        DB::table("users")
            ->where("role_id", 1)
            ->update(["role" => "client"]);
        DB::table("users")
            ->where("role_id", 2)
            ->update(["role" => "admin"]);

        Schema::table("users", function (Blueprint $table) {
            $table->dropForeign("users_role_id_foreign");
            $table->dropColumn("role_id");
        });

        Schema::dropIfExists("role");
    }
};
