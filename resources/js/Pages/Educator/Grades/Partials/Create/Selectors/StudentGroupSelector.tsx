import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Label,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import React, { useEffect, useState } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { StudentGroupDto } from '@/types/dtos/educator/student-group.dto';
import { UserGroupIcon } from '@heroicons/react/24/outline';
import InputError from '@/Components/Forms/InputError';

interface StudentGroupSelectorProps {
    className?: string;
    value: StudentGroupDto | null;
    onChange: (newValue: StudentGroupDto | null) => void;
    disabled?: boolean;
    institutionKey?: string | undefined;
    errors?: string | undefined;
}

export default function StudentGroupSelector({
    className,
    value,
    onChange,
    disabled,
    institutionKey,
    errors,
}: StudentGroupSelectorProps) {
    const [query, setQuery] = useState('');
    const [filteredStudentGroups, setFilteredStudentGroups] = useState<
        StudentGroupDto[]
    >([]);

    useEffect(() => {
        const queryParams: Record<string, string> = {};

        if (query.length) {
            queryParams.searchQuery = query;
        }

        if (institutionKey) {
            queryParams.descendantOfInstitutionIds = institutionKey;
        }

        axios
            .get<
                PaginatedCollection<StudentGroupDto>
            >(route('api.educator.studentGroups.index', queryParams))
            .then(
                response => {
                    setFilteredStudentGroups(response.data.data);
                },
                () => {
                    // Do nothing
                }
            );
    }, [query, institutionKey]);

    return (
        <div className={combineClassNames(disabled && 'opacity-50')}>
            <Combobox
                as="div"
                value={value}
                disabled={disabled}
                onChange={newValue => {
                    setQuery('');
                    onChange(newValue);
                }}>
                <Label className="block text-sm font-medium leading-6 text-gray-900">
                    Student group
                </Label>

                {value && (
                    <nav className="text-gray-500">
                        <ol className="flex flex-wrap items-center gap-x-1">
                            {value.ancestors.map(ancestor => (
                                <li
                                    key={ancestor.id}
                                    className="flex items-center">
                                    <span className="mr-1 text-xs font-medium">
                                        {ancestor.name}
                                    </span>
                                    <svg
                                        className="size-3 flex-shrink-0"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        aria-hidden="true">
                                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                    </svg>
                                </li>
                            ))}
                        </ol>
                    </nav>
                )}

                <div className="relative mt-2">
                    <ComboboxInput
                        className={combineClassNames(
                            className,
                            'w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'
                        )}
                        onChange={event => {
                            setQuery(event.target.value);
                        }}
                        onBlur={() => {
                            setQuery('');
                        }}
                        placeholder="Start typing to get suggestions"
                        displayValue={(value: StudentGroupDto | null) =>
                            value?.name ?? ''
                        }
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>

                    {filteredStudentGroups.length > 0 && (
                        <ComboboxOptions className="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            {filteredStudentGroups.map(studentGroup => (
                                <ComboboxOption
                                    key={studentGroup.id}
                                    value={studentGroup}
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
                                                        <ol className="flex flex-wrap items-center gap-x-1">
                                                            {studentGroup.ancestors.map(
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
                                                        {studentGroup.name}
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
                {/* Errors */}
                {filteredStudentGroups.length === 0 &&
                !!institutionKey &&
                !value ? (
                    <p
                        className="mt-2 text-sm text-red-600"
                        id="student-group-error">
                        You are not teaching any student groups of this
                        institution.
                    </p>
                ) : (
                    <InputError message={errors} className="mt-2" />
                )}
            </Combobox>
        </div>
    );
}
