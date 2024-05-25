<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /** @throws RuntimeException */
    public function up(): void
    {
        Schema::create('roles', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('key')->index()->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    /** @throws RuntimeException */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
