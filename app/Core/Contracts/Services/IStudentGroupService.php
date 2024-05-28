<?php

declare(strict_types=1);

namespace App\Core\Contracts\Services;

use App\Core\Models\{Identity, StudentGroup};

interface IStudentGroupService
{
    /**
     * Check if a {@link $identity} can delete the given {@link $studentGroup student group}.
     *
     * @see static::canBeDeleted()
     */
    public function canBeDeletedByIdentity(
        StudentGroup $studentGroup,
        Identity $identity,
    ): bool;

    /**
     * Check if a {@link $studentGroup student group} can be deleted.
     *
     * @see static::canBeDeletedByIdentity()
     */
    public function canBeDeleted(StudentGroup $studentGroup): bool;
}
