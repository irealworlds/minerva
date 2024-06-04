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
        Schema::create('institution_disciplines', function (
            Blueprint $table,
        ): void {
            $table
                ->foreignUuid('institution_id')
                ->constrained()
                ->references('id')
                ->on('institutions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignUuid('discipline_id')
                ->constrained()
                ->references('id')
                ->on('disciplines')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->primary(['institution_id', 'discipline_id']);

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
        Schema::dropIfExists('institution_disciplines');
    }
};
