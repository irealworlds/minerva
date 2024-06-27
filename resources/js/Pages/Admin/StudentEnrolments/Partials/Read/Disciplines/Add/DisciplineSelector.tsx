import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Label,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { useEffect, useState } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import Spinner from '@/Components/Spinner';
import { useDebouncedCallback } from 'use-debounce';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { StudentGroupDisciplineDto } from '@/types/dtos/student-group-discipline.dto';

interface DisciplineSelectorProps {
    disabled?: boolean;

    studentGroupKey: string;

    value: StudentGroupDisciplineDto | null;
    onChange: (value: StudentGroupDisciplineDto | null) => void;
}

export default function DisciplineSelector({
    disabled,

    studentGroupKey,

    value,
    onChange,
}: DisciplineSelectorProps) {
    const [query, setQuery] = useState('');
    const [fetchingSuggestions, setFetchingSuggestions] = useState(false);
    const [filteredDisciplines, setFilteredDisciplines] = useState<
        StudentGroupDisciplineDto[]
    >([]);

    const updateSuggestions = useDebouncedCallback((searchQuery: string) => {
        const query: {
            search?: string;
        } = {};

        if (searchQuery.length) {
            query.search = searchQuery;
        }

        setFetchingSuggestions(true);
        axios
            .get<{ results: PaginatedCollection<StudentGroupDisciplineDto> }>(
                route('api.admin.student_groups.disciplines.index', {
                    ...query,
                    studentGroup: studentGroupKey,
                })
            )
            .then(response => {
                setFilteredDisciplines(response.data.results.data);
            })
            .catch(() => {
                // Do nothing
            })
            .finally(() => {
                setFetchingSuggestions(false);
            });
    }, 400);

    useEffect(() => {
        updateSuggestions(query);
    }, [query]);

    return (
        <div className={combineClassNames(disabled && 'opacity-50')}>
            <Combobox
                as="div"
                value={value}
                disabled={disabled}
                onChange={person => {
                    setQuery('');
                    onChange(person);
                }}>
                <Label className="block text-sm font-medium leading-6 text-gray-900">
                    Discipline
                </Label>
                <div className="relative mt-2">
                    <ComboboxInput
                        className="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
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
                    <div className="absolute inset-y-0 right-0 flex items-center gap-2 rounded-r-md px-2 focus:outline-none">
                        {fetchingSuggestions && <Spinner className="size-5" />}
                        <ComboboxButton className="">
                            <ChevronUpDownIcon
                                className="h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                        </ComboboxButton>
                    </div>

                    {filteredDisciplines.length > 0 && (
                        <ComboboxOptions className="absolute z-[60] mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            {filteredDisciplines.map(discipline => (
                                <ComboboxOption
                                    key={discipline.id}
                                    value={discipline}
                                    className={({ focus }) =>
                                        combineClassNames(
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
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
            </Combobox>
        </div>
    );
}
