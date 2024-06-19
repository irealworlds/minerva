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
        Schema::create('student_group_enrolments', function (
            Blueprint $table,
        ): void {
            $table->uuid('id');
            $table->primary('id');
            $table
                ->foreignUuid('student_group_id')
                ->constrained()
                ->references('id')
                ->on('student_groups')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignUuid('student_registration_id')
                ->constrained()
                ->references('id')
                ->on('student_registrations')
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
        Schema::dropIfExists('student_group_enrolments');
    }
};
