<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @throws RuntimeException
     */
    public function up(): void
    {
        Schema::table('identities', function (Blueprint $table): void {
            $table->string('username')->after('id');
            $table->string('normalized_username')->after('username')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @throws RuntimeException
     */
    public function down(): void
    {
        Schema::table('identities', function (Blueprint $table): void {
            $table->dropColumn(['username', 'normalized_username']);
        });
    }
};
