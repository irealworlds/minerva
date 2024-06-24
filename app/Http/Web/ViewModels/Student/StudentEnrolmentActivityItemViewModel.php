<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Student;

use App\Core\Enums\StudentEnrolmentActivityType;
use Carbon\Carbon;

final readonly class StudentEnrolmentActivityItemViewModel
{
    function __construct(
        public StudentEnrolmentActivityType $type,
        public mixed $properties,
        public Carbon $date,
    ) {
    }
}
