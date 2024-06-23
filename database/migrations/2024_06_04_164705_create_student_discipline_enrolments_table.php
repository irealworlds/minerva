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
        Schema::create('student_discipline_enrolments', function (
            Blueprint $table,
        ): void {
            $table->uuid('id');
            $table->primary('id');
            $table
                ->foreignUuid('student_group_enrolment_id')
                ->constrained()
                ->references('id')
                ->on('student_group_enrolments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignUuid('discipline_id')
                ->constrained()
                ->references('id')
                ->on('disciplines')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignUuid('educator_id')
                ->constrained()
                ->references('id')
                ->on('educators')
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
        Schema::dropIfExists('student_discipline_enrolments');
    }
};
