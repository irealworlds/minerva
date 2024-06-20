<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @throws RuntimeException
     */
    public function up(): void
    {
        Schema::table('student_groups', function (Blueprint $table): void {
            $table
                ->uuid('parent_institution_id')
                ->after('parent_id')
                ->nullable();

            $table
                ->foreign('parent_institution_id')
                ->references('id')
                ->on('institutions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        // Create the function
        DB::statement(/** @lang PostgreSQL */ '
            CREATE OR REPLACE FUNCTION get_institution_parent_id(in_parent_type text, in_parent_id uuid)
            RETURNS uuid AS $$
            DECLARE
                institution_parent_id uuid;
            BEGIN
                IF in_parent_type = \'App\\Core\\Models\\Institution\' THEN
                    RETURN in_parent_id;
                ELSE
                    -- Recursively find the first parent of type institution
                    WITH RECURSIVE parent_cte AS (
                        SELECT sg.parent_type, sg.parent_id
                        FROM student_groups sg
                        WHERE sg.id = in_parent_id
                        UNION ALL
                        SELECT sg2.parent_type, sg2.parent_id
                        FROM student_groups sg2
                        INNER JOIN parent_cte pc ON sg2.id = pc.parent_id
                    )
                    SELECT pc.parent_id INTO institution_parent_id
                    FROM parent_cte pc
                    WHERE pc.parent_type = \'App\\Core\\Models\\Institution\'
                    LIMIT 1;

                    IF institution_parent_id IS NOT NULL THEN
                        RETURN institution_parent_id;
                    ELSE
                        RETURN NULL;
                    END IF;
                END IF;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Create the trigger function
        DB::statement(/** @lang PostgreSQL */ '
            CREATE OR REPLACE FUNCTION update_parent_institution_id()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.parent_institution_id := get_institution_parent_id(NEW.parent_type, NEW.parent_id);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Create the trigger
        DB::statement(/** @lang PostgreSQL */ '
            CREATE TRIGGER trg_update_parent_institution_id
            BEFORE INSERT OR UPDATE ON student_groups
            FOR EACH ROW
            EXECUTE FUNCTION update_parent_institution_id();
        ');

        // Update existing records
        DB::statement(/** @lang PostgreSQL */ '
            UPDATE student_groups
            SET parent_institution_id = get_institution_parent_id(parent_type, parent_id)
            where parent_institution_id is null;
        ');

        Schema::table('student_groups', function (Blueprint $table): void {
            $table->uuid('parent_institution_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @throws RuntimeException
     */
    public function down(): void
    {
        // Drop the trigger
        DB::statement(
            /** @lang PostgreSQL */
            'DROP TRIGGER IF EXISTS trg_update_parent_institution_id ON student_groups',
        );

        // Drop the trigger function
        DB::statement(
            /** @lang PostgreSQL */
            'DROP FUNCTION IF EXISTS update_parent_institution_id()',
        );

        // Drop the get_institution_parent_id function
        DB::statement(
            /** @lang PostgreSQL */
            'DROP FUNCTION IF EXISTS get_institution_parent_id(text, uuid)',
        );

        // Drop the column
        Schema::table('student_groups', function (Blueprint $table): void {
            $table->dropColumn('parent_institution_id');
        });
    }
};
