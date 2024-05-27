<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /** @throws RuntimeException */
    public function up(): void
    {
        Schema::create('role_permissions', static function (
            Blueprint $table,
        ): void {
            $table
                ->foreignUuid('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('permission', 64);
            $table->timestamps();

            $table->primary(['permission', 'role_id']);
        });
    }

    /** @throws RuntimeException */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
