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
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { InstitutionDto } from '@/types/dtos/educator/institution.dto';
import InstitutionPicture from '@/Components/Institutions/InstitutionPicture';

interface InstitutionSelectorProps {
    className?: string;
    value: InstitutionDto | null;
    onChange: (newValue: InstitutionDto | null) => void;
    disabled?: boolean;
}

export default function InstitutionSelector({
    className,
    value,
    onChange,
    disabled,
}: InstitutionSelectorProps) {
    const [query, setQuery] = useState('');
    const [filteredInstitutions, setFilteredInstitutions] = useState<
        InstitutionDto[]
    >([]);

    useEffect(() => {
        const queryParams: Record<string, string> = {};

        if (query.length) {
            queryParams.searchQuery = query;
        }

        axios
            .get<
                PaginatedCollection<InstitutionDto>
            >(route('api.educator.institutions.index', queryParams))
            .then(
                response => {
                    setFilteredInstitutions(response.data.data);
                },
                () => {
                    // Do nothing
                }
            );
    }, [query]);

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
                    Institution
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
                        displayValue={(value: InstitutionDto | null) =>
                            value?.name ?? ''
                        }
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>

                    {filteredInstitutions.length > 0 && (
                        <ComboboxOptions className="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            {filteredInstitutions.map(institution => (
                                <ComboboxOption
                                    key={institution.id}
                                    value={institution}
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
                                            <div className="flex items-center">
                                                <InstitutionPicture
                                                    uri={institution.pictureUri}
                                                    className="size-6 shrink-0 rounded-full"
                                                />
                                                <span
                                                    className={combineClassNames(
                                                        'ml-3 truncate',
                                                        selected &&
                                                            'font-semibold'
                                                    )}>
                                                    {institution.name}
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
