<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

final readonly class StudentGradeViewModel
{
    /**
     * @param object{name: string} $awardedBy
     */
    function __construct(
        public mixed $key,
        public object $awardedBy,
        public float $awardedPoints,
        public float $maximumPoints,
        public string $notes,
        public string $awardedAt,
    ) {
    }
}
