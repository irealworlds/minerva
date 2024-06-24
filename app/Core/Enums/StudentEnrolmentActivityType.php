<?php

declare(strict_types=1);

namespace App\Core\Enums;

enum StudentEnrolmentActivityType: string
{
    case StudentGroupEnrolment = 'student_group_enrolment';
    case NewGrade = 'new_grade';
}
