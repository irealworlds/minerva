<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\Services\IStudentGroupService;
use App\Core\Enums\Permission;
use App\Core\Models\{Identity, StudentGroup};

final readonly class StudentGroupService implements IStudentGroupService
{
    /**
     * @inheritDoc
     */
    public function canBeDeletedByIdentity(
        StudentGroup $studentGroup,
        Identity $identity,
    ): bool {
        // If the group cannot be deleted by anyone, fail the check automatically
        if (!$this->canBeDeleted($studentGroup)) {
            return false;
        }

        // Require the right permissions
        return ! (!$identity->hasPermission(Permission::StudentGroupDelete))



        ;
    }

    /**
     * @inheritDoc
     */
    public function canBeDeleted(StudentGroup $studentGroup): bool
    {
        // If this student group has children, deletion is prohibited
        return ! ($studentGroup->childGroups()->exists())



        ;
    }
}
