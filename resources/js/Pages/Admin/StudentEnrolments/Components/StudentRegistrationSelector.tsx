import { StudentRegistrationDto } from '@/types/dtos/student-registration.dto';
import React, { useEffect, useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Field,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import InputLabel from '@/Components/Forms/InputLabel';

interface StudentRegistrationSelectorProps {
    className?: string;
    value: StudentRegistrationDto | null;
    onChange: (newValue: StudentRegistrationDto | null) => void;
    disabled?: boolean;
}

function displaySuggestion(
    suggestion: StudentRegistrationDto,
    selected: boolean
) {
    return (
        <div className="flex items-center gap-2">
            {/*  Picture */}
            <img
                src={suggestion.pictureUri}
                className="size-8 bg-gray-200 flex items-center justify-center rounded-full text-white shrink-0"
                aria-hidden="true"
                alt="picture"
            />

            {/* Text */}
            <div className="grow max-w-full">
                <p
                    className={combineClassNames(
                        'truncate',
                        selected ? 'font-semibold' : ''
                    )}>
                    {suggestion.name}
                </p>
            </div>
        </div>
    );
}

export default function StudentRegistrationSelector({
    value,
    onChange,
    disabled,
    className,
}: StudentRegistrationSelectorProps) {
    const [query, setQuery] = useState('');
    const [suggestions, setSuggestions] = useState<StudentRegistrationDto[]>(
        []
    );

    const updateSuggestions = useDebouncedCallback((searchQuery: string) => {
        const query: Record<string, string> = {};

        if (searchQuery.length) {
            query.searchQuery = searchQuery;
        }

        axios
            .get<{
                results: PaginatedCollection<StudentRegistrationDto>;
            }>(route('api.admin.student_registrations.index', query))
            .then(response => {
                setSuggestions(response.data.results.data);
            })
            .catch(() => {
                // Do nothing
            });
    }, 400);

    useEffect(() => {
        updateSuggestions(query);
    }, [query]);

    return (
        <Field className={className}>
            <InputLabel>Student registration:</InputLabel>
            <Combobox
                as="div"
                disabled={disabled}
                value={value}
                onChange={newValue => {
                    setQuery('');
                    onChange(newValue);
                }}>
                <div className="relative mt-2">
                    <ComboboxInput
                        placeholder="Search typing to get suggestions"
                        className="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        onChange={event => {
                            setQuery(event.target.value);
                        }}
                        onBlur={() => {
                            setQuery('');
                        }}
                        displayValue={(
                            registration: StudentRegistrationDto | null
                        ) => registration?.name ?? ''}
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>

                    {suggestions.length > 0 && (
                        <ComboboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            {suggestions.map(suggestedRegistration => (
                                <ComboboxOption
                                    key={suggestedRegistration.id}
                                    value={suggestedRegistration}
                                    className={({ focus }) =>
                                        combineClassNames(
                                            'relative cursor-pointer select-none py-2 pl-3 pr-9',
                                            focus
                                                ? 'bg-indigo-600 text-white'
                                                : 'text-gray-900'
                                        )
                                    }>
                                    {({ focus, selected }) => (
                                        <>
                                            {displaySuggestion(
                                                suggestedRegistration,
                                                selected
                                            )}

                                            {/*  Check icon (if selected) */}
                                            {selected && (
                                                <span
                                                    className={combineClassNames(
                                                        'absolute inset-y-0 right-0 flex items-center pr-4',
                                                        focus
                                                            ? 'text-white'
                                                            : 'text-indigo-600'
                                                    )}>
                                                    <CheckIcon
                                                        className="h-5 w-5"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            )}
                                        </>
                                    )}
                                </ComboboxOption>
                            ))}
                        </ComboboxOptions>
                    )}
                </div>
            </Combobox>
        </Field>
    );
}
