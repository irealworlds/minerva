import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import React, { useContext, useEffect, useState } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import InputError from '@/Components/Forms/InputError';
import { StudentGroupEnrolmentManagementContext } from '@/Pages/Student/Enrolments/Partials/Read/ReadLayout';
import { DisciplineDto } from '@/types/dtos/student/discipline.dto';

interface DisciplineSelectorProps {
    className?: string;
    disabled?: boolean;
    errors?: string | undefined;

    value: DisciplineDto | null;
    onChange: (newValue: DisciplineDto | null) => void;
}

export default function DisciplineSelector({
    className,
    value,
    onChange,
    disabled,
    errors,
}: DisciplineSelectorProps) {
    const [query, setQuery] = useState('');
    const [filteredDisciplines, setFilteredDisciplines] = useState<
        DisciplineDto[]
    >([]);
    const { enrolment } = useContext(StudentGroupEnrolmentManagementContext);

    useEffect(() => {
        if (!enrolment) {
            throw new Error('Enrolment is not provided');
        }

        const queryParams: Record<string, string> = {};

        if (query.length) {
            queryParams.searchQuery = query;
        }

        axios
            .get<PaginatedCollection<DisciplineDto>>(
                route('api.student.studentGroupEnrolments.disciplines.index', {
                    enrolment: enrolment.key,
                    ...queryParams,
                })
            )
            .then(
                response => {
                    setFilteredDisciplines(response.data.data);
                },
                () => {
                    // Do nothing
                }
            );
    }, [query]);

    return (
        <div className={combineClassNames(className, disabled && 'opacity-50')}>
            <Combobox
                as="div"
                value={value}
                disabled={disabled}
                onChange={newValue => {
                    setQuery('');
                    onChange(newValue);
                }}>
                <div className="relative mt-2">
                    <ComboboxInput
                        className={combineClassNames(
                            'w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'
                        )}
                        onChange={event => {
                            setQuery(event.target.value);
                        }}
                        onBlur={() => {
                            setQuery('');
                        }}
                        placeholder="Start typing to get suggestions"
                        displayValue={(value: DisciplineDto | null) =>
                            value?.name ?? 'All disciplines'
                        }
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>

                    <ComboboxOptions className="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                        <ComboboxOption
                            value={null}
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
                                    <div className="flex">
                                        <span
                                            className={combineClassNames(
                                                'truncate',
                                                selected && 'font-semibold'
                                            )}>
                                            All disciplines
                                        </span>
                                    </div>

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
                        {filteredDisciplines.map(discipline => (
                            <ComboboxOption
                                key={discipline.id}
                                value={discipline}
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
                                        <div className="flex">
                                            <span
                                                className={combineClassNames(
                                                    'truncate',
                                                    selected && 'font-semibold'
                                                )}>
                                                {discipline.name}
                                            </span>
                                            <span
                                                className={combineClassNames(
                                                    'ml-2 truncate text-gray-500',
                                                    focus
                                                        ? 'text-indigo-200'
                                                        : 'text-gray-500'
                                                )}>
                                                {discipline.abbreviation}
                                            </span>
                                        </div>

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
                </div>

                {/* Errors */}
                <InputError message={errors} className="mt-2" />
            </Combobox>
        </div>
    );
}
