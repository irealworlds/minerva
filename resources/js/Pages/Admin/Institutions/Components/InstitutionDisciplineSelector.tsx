import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/react';
import {
    CheckIcon,
    ChevronUpDownIcon,
    PlusIcon,
} from '@heroicons/react/20/solid';
import React, { useEffect, useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import { Link } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

interface InstitutionDisciplineSelectorProps {
    value: DisciplineDto | null;
    onChange: (newValue: DisciplineDto | null) => void;
    disabled?: boolean;
    className?: string;
    institution?: InstitutionViewModel;
}

export default function InstitutionDisciplineSelector({
    value,
    onChange,
    disabled,
    className,
    institution,
}: InstitutionDisciplineSelectorProps) {
    const [query, setQuery] = useState('');
    const [suggestions, setSuggestions] = useState<DisciplineDto[]>([]);

    const updateSuggestions = useDebouncedCallback((searchQuery: string) => {
        const query: {
            search?: string;
        } = {};

        if (searchQuery.length) {
            query.search = searchQuery;
        }

        axios
            .get<{
                disciplines: PaginatedCollection<DisciplineDto>;
            }>(
                route('api.disciplines.index', {
                    query,
                    notAssociatedToInstitutionIds: institution?.id,
                })
            )
            .then(response => {
                setSuggestions(response.data.disciplines.data);
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
            className={className}
            value={value}
            onChange={newValue => {
                setQuery('');
                onChange(newValue);
            }}>
            <div className="relative">
                <ComboboxInput
                    placeholder="Search typing to get suggestions"
                    className="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    onChange={event => {
                        setQuery(event.target.value);
                    }}
                    onBlur={() => {
                        setQuery('');
                    }}
                    displayValue={(discipline: DisciplineDto | null) =>
                        discipline?.name ?? ''
                    }
                />
                <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                    <ChevronUpDownIcon
                        className="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </ComboboxButton>

                <ComboboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <Link
                        href={route('admin.disciplines.create', {
                            institutions: institution?.id ?? '',
                        })}
                        className="relative cursor-pointer select-none py-2 pl-3 pr-9 flex items-center hover:bg-gray-100">
                        <span className="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dashed border-gray-300 text-gray-400">
                            <PlusIcon className="size-4" aria-hidden="true" />
                        </span>
                        <div className="ml-4 text-sm">
                            <p className="text-gray-500">
                                Create new discipline{' '}
                                <span className="font-medium">{query}</span>
                            </p>
                            <p className="text-xs text-gray-400">
                                and associate it with this institution
                            </p>
                        </div>
                    </Link>
                    {suggestions.map(suggestedDiscipline => (
                        <ComboboxOption
                            key={suggestedDiscipline.id}
                            value={suggestedDiscipline}
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
                                        {/* Text */}
                                        <p
                                            className={combineClassNames(
                                                'truncate',
                                                selected ? 'font-semibold' : ''
                                            )}>
                                            {suggestedDiscipline.name}
                                        </p>
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
            </div>
        </Combobox>
    );
}
