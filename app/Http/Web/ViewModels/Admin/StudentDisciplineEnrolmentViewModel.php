<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Admin;

final readonly class StudentDisciplineEnrolmentViewModel
{
    /**
     * @param iterable<mixed> $enrolmentKeys
     * @param iterable<object{
     *     key: mixed,
     *     name: string,
     *     pictureUri: string
     * }> $educators
     */
    public function __construct(
        public iterable $enrolmentKeys,
        public mixed $disciplineKey,
        public string $disciplineName,
        public string|null $disciplineAbbreviation,
        public string $disciplinePictureUri,
        public iterable $educators,
        public int $gradesCount,
        public float|null $averageGrade,
    ) {
    }
}
