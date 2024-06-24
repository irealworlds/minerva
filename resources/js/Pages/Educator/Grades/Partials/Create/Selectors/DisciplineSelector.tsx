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
import { DisciplineDto } from '@/types/dtos/educator/discipline.dto';
import InputError from '@/Components/Forms/InputError';

interface DisciplineSelectorProps {
    className?: string;
    value: DisciplineDto | null;
    onChange: (newValue: DisciplineDto | null) => void;
    disabled?: boolean;
    studentGroupKey?: string | null;
    errors?: string | undefined;
}

export default function DisciplineSelector({
    className,
    value,
    onChange,
    disabled,
    studentGroupKey,
    errors,
}: DisciplineSelectorProps) {
    const [query, setQuery] = useState('');
    const [filteredDisciplines, setFilteredDisciplines] = useState<
        DisciplineDto[]
    >([]);

    useEffect(() => {
        const queryParams: Record<string, string> = {};

        if (query.length) {
            queryParams.searchQuery = query;
        }

        if (studentGroupKey) {
            queryParams.studentGroupKey = studentGroupKey;
        }

        axios
            .get<
                PaginatedCollection<DisciplineDto>
            >(route('api.educator.disciplines.index', queryParams))
            .then(
                response => {
                    setFilteredDisciplines(response.data.data);
                },
                () => {
                    // Do nothing
                }
            );
    }, [query, studentGroupKey]);

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
                    Discipline
                </Label>
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
                        displayValue={(value: DisciplineDto | null) =>
                            value?.name ?? ''
                        }
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>

                    {filteredDisciplines.length > 0 && (
                        <ComboboxOptions className="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
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
                                                        selected &&
                                                            'font-semibold'
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
                    )}
                </div>

                {/* Errors */}
                {filteredDisciplines.length === 0 &&
                !!studentGroupKey &&
                !value ? (
                    <p
                        className="mt-2 text-sm text-red-600"
                        id="student-group-error">
                        You are not teaching any disciplines to any student in
                        this student group.
                    </p>
                ) : (
                    <InputError message={errors} className="mt-2" />
                )}
            </Combobox>
        </div>
    );
}
