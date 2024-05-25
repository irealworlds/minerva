<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @throws RuntimeException */
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->uuid("id");
            $table->primary("id");

            $table->string("name");

            $table->uuid("parent_institution_id")
                ->nullable()
                ->default(null);
            $table->foreign("parent_institution_id")
                ->references("id")
                ->on("institutions")
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string("website", 64)
                ->nullable()
                ->default(null);

            $table->timestamps();
        });
    }

    /** @throws RuntimeException */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
