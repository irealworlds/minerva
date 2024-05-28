<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\UpdateDetails;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\StudentGroup;
use App\Core\Optional;

final readonly class UpdateStudentGroupDetailsCommand implements ICommand
{
    /**
     * @param Optional<string> $name
     */
    public function __construct(
        public StudentGroup $studentGroup,
        public Optional $name,
    ) {
    }
}
