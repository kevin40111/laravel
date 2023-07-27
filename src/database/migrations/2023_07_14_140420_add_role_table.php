<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::create("role", function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->timestamps();
            });

            Role::insert([
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

            Schema::table("users", function (Blueprint $table) {
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

            User::where("role", "client")->update(["role_id" => 1]);
            User::where("role", "admin")->update(["role_id" => 2]);

            Schema::table("users", function (Blueprint $table) {
                $table->dropColumn("role");
                $table
                    ->unsignedBigInteger("role_id")
                    ->default(1)
                    ->change();
            });
        } catch (PDOException $exception) {
            $this->down();
            throw $exception;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->string("role")->default("client");
        });

        User::where("role_id", 1)->update(["role" => "client"]);
        User::where("role_id", 2)->update(["role" => "admin"]);

        Schema::table("users", function (Blueprint $table) {
            $table->dropForeign("users_role_id_foreign");
            $table->dropColumn("role_id");
        });

        Schema::dropIfExists("role");
    }
};
