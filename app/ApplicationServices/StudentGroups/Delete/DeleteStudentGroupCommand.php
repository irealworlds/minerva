<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\Delete;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Identity, StudentGroup};

final readonly class DeleteStudentGroupCommand implements ICommand
{
    public function __construct(
        public StudentGroup $studentGroup,
        public Identity|null $initiator = null,
    ) {
    }
}
