<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name')->nullable(); // Nullable for existing users initially, or we can default. Let's make it nullable first to avoid issues, or unique but we need to handle existing data. 
            // Actually, if I make it unique, existing rows with null might conflict if DB doesn't support multiple nulls (MySQL does).
            // But better to perhaps not enforce unique immediately if there is data? 
            // The plan said "existing users might need a username populated". 
            // I'll make it nullable for now, or just add it. 
            // Let's stick to the plan: "Add username column (string, unique). Make email column nullable."
            // To be safe with existing data, I should probably make it nullable first, or populate it.
            // Given I can't easily populate in migration without more code, I'll make it nullable for now, but the goal is to use it for login.
            // Let's try to make it nullable first.
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->string('email')->nullable(false)->change();
        });
    }
};
