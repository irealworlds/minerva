import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import React, { useEffect, useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { BuildingLibraryIcon } from '@heroicons/react/24/outline';

interface ParentInstitutionSelectorProps {
    value: InstitutionViewModel | null;
    onChange: (newValue: InstitutionViewModel | null) => void;
    disabled?: boolean;
}

export default function ParentInstitutionSelector({
    value,
    onChange,
    disabled,
}: ParentInstitutionSelectorProps) {
    const [query, setQuery] = useState('');
    const [suggestions, setSuggestions] = useState<InstitutionViewModel[]>([]);

    const updateSuggestions = useDebouncedCallback((searchQuery: string) => {
        const query: {
            search?: string;
        } = {};

        if (searchQuery.length) {
            query.search = searchQuery;
        }

        axios
            .get<{
                institutions: PaginatedCollection<InstitutionViewModel>;
            }>(route('api.institutions.index', query))
            .then(response => {
                setSuggestions(response.data.institutions.data);
            })
            .catch(() => {
                // Do nothing
            });
    }, 400);

    useEffect(() => {
        updateSuggestions(query);
    }, [query]);

    return (
        <Combobox
            disabled={disabled}
            as="div"
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
                    displayValue={(institution: InstitutionViewModel | null) =>
                        institution?.name ?? ''
                    }
                />
                <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                    <ChevronUpDownIcon
                        className="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </ComboboxButton>

                {suggestions.length > 0 && (
                    <ComboboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                        {suggestions.map(suggestedInstitution => (
                            <ComboboxOption
                                key={suggestedInstitution.id}
                                value={suggestedInstitution}
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
                                        <div className="flex items-center gap-2">
                                            {/*  Picture */}
                                            {suggestedInstitution.pictureUri ? (
                                                <img
                                                    src={
                                                        suggestedInstitution.pictureUri
                                                    }
                                                    alt={
                                                        suggestedInstitution.name
                                                    }
                                                    className="size-8 rounded-full bg-gray-50"
                                                />
                                            ) : (
                                                <div
                                                    className="size-8 bg-gray-800 flex items-center justify-center rounded-full text-white"
                                                    aria-hidden="true">
                                                    <BuildingLibraryIcon className="size-6" />
                                                </div>
                                            )}

                                            {/* Text */}
                                            <div>
                                                <nav
                                                    className={combineClassNames(
                                                        'truncate',
                                                        focus
                                                            ? 'text-indigo-200'
                                                            : 'text-gray-500'
                                                    )}>
                                                    <ol className="flex items-center space-x-1">
                                                        {suggestedInstitution.ancestors.map(
                                                            ancestor => (
                                                                <li
                                                                    key={
                                                                        ancestor.id
                                                                    }
                                                                    className="flex items-center">
                                                                    <span className="mr-1 text-xs font-medium">
                                                                        {
                                                                            ancestor.name
                                                                        }
                                                                    </span>
                                                                    <svg
                                                                        className="size-3 flex-shrink-0"
                                                                        fill="currentColor"
                                                                        viewBox="0 0 20 20"
                                                                        aria-hidden="true">
                                                                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                                                    </svg>
                                                                </li>
                                                            )
                                                        )}
                                                    </ol>
                                                </nav>
                                                <p
                                                    className={combineClassNames(
                                                        'truncate',
                                                        selected
                                                            ? 'font-semibold'
                                                            : ''
                                                    )}>
                                                    {suggestedInstitution.name}
                                                </p>
                                            </div>
                                        </div>

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
    );
}
