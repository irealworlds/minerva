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
        Schema::table('identities', function (Blueprint $table): void {
            $table
                ->string('name_prefix', 64)
                ->nullable()
                ->default(null)
                ->after('email');
            $table->string('first_name', 64)->after('name_prefix');
            $table->json('middle_names')->default('[]')->after('first_name');
            $table->string('last_name', 64)->after('middle_names');
            $table
                ->string('name_suffix', 64)
                ->nullable()
                ->default(null)
                ->after('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @throws RuntimeException
     */
    public function down(): void
    {
        Schema::table('identities', function (Blueprint $table): void {
            $table->dropColumn([
                'name_prefix',
                'first_name',
                'middle_names',
                'last_name',
                'name_suffix',
            ]);
        });
    }
};
