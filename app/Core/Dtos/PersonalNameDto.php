<?php

declare(strict_types=1);

namespace App\Core\Dtos;

use Illuminate\Support\Str;

final readonly class PersonalNameDto
{
    /**
     * @param string[] $middleNames
     */
    public function __construct(
        public string|null $prefix,
        public string $firstName,
        public array $middleNames,
        public string $lastName,
        public string|null $suffix,
    ) {
    }

    public function getFullName(): string
    {
        $name = implode(
            ' ',
            array_filter([
                $this->prefix,
                $this->firstName,
                implode(' ', $this->middleNames),
                $this->lastName,
            ]),
        );

        if (!empty($this->suffix)) {
            $name = $name . ', ' . $this->suffix;
        }

        return $name;
    }

    public function getDirectoryName(): string
    {
        return Str::upper($this->lastName) . ', ' . $this->firstName;
    }
}
