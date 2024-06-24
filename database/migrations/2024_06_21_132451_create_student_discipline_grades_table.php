<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @throws RuntimeException
     */
    public function up(): void
    {
        Schema::create('student_discipline_grades', function (
            Blueprint $table,
        ) {
            $table->uuid('id');
            $table->primary('id');

            $table
                ->foreignUuid('student_id')
                ->references('id')
                ->on('student_registrations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignUuid('student_group_id')
                ->references('id')
                ->on('student_groups')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignUuid('discipline_id')
                ->references('id')
                ->on('disciplines')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('awarded_points');
            $table->decimal('maximum_points');

            $table->text('notes');

            $table
                ->foreignUuid('educator_id')
                ->references('id')
                ->on('educators')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->date('awarded_at');
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
        Schema::dropIfExists('student_discipline_grades');
    }
};
