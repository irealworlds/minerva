<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, Pivot};

/**
 * @property mixed $student_group_id
 * @property mixed $discipline_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Discipline $discipline
 * @property-read StudentGroup $studentGroup
 */
final class StudentGroupDiscipline extends Pivot
{
    protected $fillable = ['student_group_id', 'discipline_id'];

    protected $table = 'student_group_disciplines';

    /**
     * @return BelongsTo<StudentGroup, self>
     */
    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    /**
     * @return BelongsTo<Discipline, self>
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class, 'discipline_id');
    }

    /**
     * @return BelongsToMany<Educator>
     */
    public function educators(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Educator::class,
            table: (new StudentGroupDisciplineEducator())->getTable(),
            foreignPivotKey: (new StudentGroupDisciplineEducator())
                ->discipline()
                ->getForeignKeyName(),
            parentKey: 'discipline_id',
        )->wherePivot(
            column: (new StudentGroupDisciplineEducator())
                ->studentGroup()
                ->getForeignKeyName(),
            operator: '=',
            value: $this->student_group_id,
        );
    }
}
