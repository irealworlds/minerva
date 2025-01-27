<?php

declare(strict_types=1);

namespace App\Core\Traits\Requests;

use App\Core\Optional;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

use function is_array;
use function is_string;

trait WithOptionals
{
    /**
     * Get a value from the request as an {@link Optional}.
     *
     * @return ($nullable is true ? Optional<string|null> : Optional<string>)
     *
     * @throws ValidationException
     */
    public function optionalString(string $key, bool $nullable = true): Optional
    {
        if ($this->has($key)) {
            $value = $this->get($key);

            if ($value === null && !$nullable) {
                throw ValidationException::withMessages([
                    $key => __('validation.required', ['attribute' => $key]),
                ]);
            }

            if ($value === null || is_string($value)) {
                return Optional::of($value);
            } else {
                throw ValidationException::withMessages([
                    $key => __('validation.string', ['attribute' => $key]),
                ]);
            }
        } else {
            return Optional::empty();
        }
    }

    /**
     * Get a value from the request as an {@link Optional}.
     *
     * @return ($nullable is true ? Optional<mixed[]|null> : Optional<mixed[]>)
     *
     * @throws ValidationException
     */
    public function optionalArray(string $key, bool $nullable = true): Optional
    {
        if ($this->has($key)) {
            $value = $this->get($key);

            if ($value === null && !$nullable) {
                throw ValidationException::withMessages([
                    $key => __('validation.required', ['attribute' => $key]),
                ]);
            }

            if ($value === null || is_array($value)) {
                return Optional::of($value);
            } else {
                throw ValidationException::withMessages([
                    $key => __('validation.array', ['attribute' => $key]),
                ]);
            }
        } else {
            return Optional::empty();
        }
    }

    /**
     * Get a value from the request as an {@link Optional}.
     *
     * @return ($nullable is true ? Optional<UploadedFile|null> : Optional<UploadedFile>)
     *
     * @throws ValidationException
     */
    public function optionalFile(string $key, bool $nullable = true): Optional
    {
        if ($this->has($key)) {
            $value = $this->file($key);

            if ($value === null && !$nullable) {
                throw ValidationException::withMessages([
                    $key => __('validation.required', ['attribute' => $key]),
                ]);
            }

            if ($value === null || $value instanceof UploadedFile) {
                return Optional::of($value);
            } else {
                throw ValidationException::withMessages([
                    $key => __('validation.file', ['attribute' => $key]),
                ]);
            }
        } else {
            return Optional::empty();
        }
    }
}
