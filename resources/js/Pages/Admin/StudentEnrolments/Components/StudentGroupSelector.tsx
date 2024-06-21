import { useDebouncedCallback } from 'use-debounce';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import React, { useContext, useEffect, useRef, useState } from 'react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
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
import { UserGroupIcon } from '@heroicons/react/24/outline';
import InputLabel from '@/Components/Forms/InputLabel';
import { StudentEnrolmentCreationContext } from '@/Pages/Admin/StudentEnrolments/Create';

interface ParentGroupSelectorProps {
    className?: string;
    value: StudentGroupViewModel | null;
    onChange: (newValue: StudentGroupViewModel | null) => void;
    disabled?: boolean;
}

export default function ParentGroupSelector({
    className,
    value,
    onChange,
    disabled,
}: ParentGroupSelectorProps) {
    const [query, setQuery] = useState('');
    const [suggestions, setSuggestions] = useState<StudentGroupViewModel[]>([]);
    const parentInstitutionId = useRef<string | null>(null);
    const { selectedInstitution } = useContext(StudentEnrolmentCreationContext);
    const [initialized, setInitialized] = useState(false);

    const updateSuggestions = useDebouncedCallback((searchQuery: string) => {
        const query: {
            search?: string;
            descendantOfInstitutionIds?: string;
        } = {};

        if (parentInstitutionId.current?.length) {
            query.descendantOfInstitutionIds = parentInstitutionId.current;
        }

        if (searchQuery.length) {
            query.search = searchQuery;
        }

        axios
            .get<{
                results: PaginatedCollection<StudentGroupViewModel>;
            }>(route('api.student_groups.index', query))
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

    useEffect(() => {
        parentInstitutionId.current = selectedInstitution?.id ?? null;

        // Reset the value if the institution changes
        if (!initialized) {
            setInitialized(true);
        } else {
            setQuery('');
            onChange(null);
            updateSuggestions('');
        }
    }, [selectedInstitution]);

    return (
        <Field className={className}>
            <InputLabel>Student group</InputLabel>
            <nav className="mb-2">
                <ol className="flex items-center space-x-1 flex-wrap text-gray-500">
                    {value?.ancestors.map(ancestor => (
                        <li
                            key={ancestor.id}
                            className="shrink-0 flex items-center">
                            <span className="mr-1 text-xs font-medium shrink-0">
                                {ancestor.name}
                            </span>
                            <svg
                                className="size-3 shrink-0"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                aria-hidden="true">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                        </li>
                    ))}
                </ol>
            </nav>
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
                        displayValue={(group: StudentGroupViewModel | null) =>
                            group?.name ?? ''
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
                            {suggestions.map(suggestedGroups => (
                                <ComboboxOption
                                    key={suggestedGroups.id}
                                    value={suggestedGroups}
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
                                                <div
                                                    className="size-8 bg-gray-800 flex items-center justify-center rounded-full text-white shrink-0"
                                                    aria-hidden="true">
                                                    <UserGroupIcon className="size-6" />
                                                </div>

                                                {/* Text */}
                                                <div className="grow max-w-full">
                                                    <nav
                                                        className={combineClassNames(
                                                            focus
                                                                ? 'text-indigo-200'
                                                                : 'text-gray-500'
                                                        )}>
                                                        <ol className="flex flex-wrap items-center space-x-1">
                                                            {suggestedGroups.ancestors.map(
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
                                                        {suggestedGroups.name}
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
        </Field>
    );
}
