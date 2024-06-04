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
        Schema::create('institution_educators', function (
            Blueprint $table,
        ): void {
            $table->uuid('id');
            $table->primary('id');

            $table
                ->foreignUuid('institution_id')
                ->constrained()
                ->references('id')
                ->on('institutions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignUuid('educator_id')
                ->constrained()
                ->references('id')
                ->on('educators')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(['institution_id', 'educator_id']);

            $table->json('roles')->default('[]');

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
        Schema::dropIfExists('institution_educators');
    }
};
