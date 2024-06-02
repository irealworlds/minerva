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
        Schema::create('educator_invitations', function (
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
                ->restrictOnDelete();

            $table
                ->foreignUuid('invited_educator_id')
                ->constrained()
                ->references('id')
                ->on('educators')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('inviter_name', 64);
            $table->string('inviter_email', 128);
            $table
                ->foreignId('inviter_id')
                ->nullable()
                ->default(null)
                ->constrained()
                ->references('id')
                ->on('identities')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->json('roles')->default('[]');
            $table->boolean('accepted')->default(false);
            $table->timestamp('responded_at')->nullable()->default(null);
            $table->timestamp('expired_at');
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
        Schema::dropIfExists('educator_invitations');
    }
};
