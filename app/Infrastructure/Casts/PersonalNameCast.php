<?php

namespace App\Infrastructure\Casts;

use App\Core\Dtos\PersonalNameDto;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use JsonException;

use function is_array;
use function is_string;

use const JSON_THROW_ON_ERROR;

/**
 * @implements CastsAttributes<PersonalNameDto, PersonalNameDto>

 */
final readonly class PersonalNameCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes,
    ): PersonalNameDto {
        // Create a new validator instance.
        /** @var ValidatorFactory $validatorFactory */
        $validatorFactory = app()->make(ValidatorFactory::class);

        // Make sure the attributes are valid.
        try {
            $validatorFactory
                ->make($attributes, [
                    'name_prefix' => ['nullable', 'string'],
                    'first_name' => ['required', 'string'],
                    'middle_names' => ['required', 'json'],
                    'last_name' => ['required', 'string'],
                    'name_suffix' => ['nullable', 'string'],
                ])
                ->validate();
        } catch (ValidationException) {
            throw new InvalidArgumentException('The attributes are invalid.');
        }

        /** @var array{
         *  name_prefix: null|string,
         *  first_name: string,
         *  middle_names: string,
         *  last_name: string,
         *  name_suffix: string|null
         * } $attributes
         */

        $middleNames = json_decode($attributes['middle_names']);

        if (!is_array($middleNames)) {
            throw new InvalidArgumentException(
                'The middle names must be an array.',
            );
        }

        $middleNames = new Collection($middleNames);

        if (!$middleNames->every(fn (mixed $item) => is_string($item))) {
            throw new InvalidArgumentException(
                'The middle names must be an array of strings.',
            );
        }
        $middleNames = $middleNames->toArray();
        /** @var array<int, string> $middleNames */

        return new PersonalNameDto(
            prefix: $attributes['name_prefix'],
            firstName: $attributes['first_name'],
            middleNames: $middleNames,
            lastName: $attributes['last_name'],
            suffix: $attributes['name_suffix'],
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     * @return array{
     *     name_prefix: null|string,
     *     first_name: string,
     *     middle_names: string,
     *     last_name: string,
     *     name_suffix: null|string
     * }
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes,
    ): array {
        if (!($value instanceof PersonalNameDto)) {
            throw new InvalidArgumentException(
                'The value must be an instance of PersonalNameDto.',
            );
        }

        return [
            'name_prefix' => $value->prefix,
            'first_name' => $value->firstName,
            'middle_names' => json_encode(
                $value->middleNames,
                JSON_THROW_ON_ERROR,
            ),
            'last_name' => $value->lastName,
            'name_suffix' => $value->suffix,
        ];
    }
}
