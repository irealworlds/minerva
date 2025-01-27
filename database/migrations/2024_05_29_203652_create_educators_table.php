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
        Schema::create('educators', function (Blueprint $table): void {
            $table->uuid('id');
            $table->primary('id');
            $table
                ->foreignId('identity_id')
                ->constrained()
                ->references('id')
                ->on('identities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @throws RuntimeException
     */
    public function down(): void
    {
        Schema::dropIfExists('educators');
    }
};
