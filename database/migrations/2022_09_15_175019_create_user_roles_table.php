<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** @throws RuntimeException */
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table): void {
            $table->morphs('user');
            $table->foreignUuid('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->primary(['user_type', 'user_id', 'role_id']);
        });
    }

    /** @throws RuntimeException */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
