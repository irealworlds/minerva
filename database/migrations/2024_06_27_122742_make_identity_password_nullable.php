<?php

declare(strict_types=1);

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
            $table->string('password')->nullable()->default(null)->change();
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
            $table->string('password')->nullable(false)->change();
        });
    }
};