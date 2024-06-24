<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Student;

final readonly class StudentGroupEnrolmentViewModel
{
    /**
     * @param iterable<object{key: mixed, name: string}> $studentGroupAncestors
     * @param iterable<object{key: mixed, name: string}> $institutionAncestors
     */
    function __construct(
        public mixed $key,
        public mixed $studentGroupKey,
        public string $studentGroupName,
        public iterable $studentGroupAncestors,
        public mixed $institutionKey,
        public string $institutionName,
        public string $institutionPictureUri,
        public iterable $institutionAncestors,
    ) {
    }
}
