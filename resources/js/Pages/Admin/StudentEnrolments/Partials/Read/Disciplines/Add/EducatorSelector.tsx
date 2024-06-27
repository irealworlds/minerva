import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Label,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { useState } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface EducatorSelectorProps {
    disabled?: boolean;

    educators: Educator[];

    value: Educator | null;
    onChange: (value: Educator | null) => void;
}

interface Educator {
    educatorId: string;
    educatorName: string;
}

export default function EducatorSelector({
    disabled,
    educators,
    value,
    onChange,
}: EducatorSelectorProps) {
    const [query, setQuery] = useState('');

    const filteredEducators =
        query === ''
            ? educators
            : educators.filter(educator => {
                  return educator.educatorName
                      .toLowerCase()
                      .includes(query.toLowerCase());
              });

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
                    Educator
                </Label>
                <div className="relative mt-2">
                    <ComboboxInput
                        className="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        onChange={event => {
                            setQuery(event.target.value);
                        }}
                        onBlur={() => {
                            setQuery('');
                        }}
                        placeholder="Start typing to get suggestions"
                        displayValue={(educator: Educator | null) =>
                            educator?.educatorName ?? ''
                        }
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                        <ChevronUpDownIcon
                            className="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>

                    {filteredEducators.length > 0 && (
                        <ComboboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            {filteredEducators.map(person => (
                                <ComboboxOption
                                    key={person.educatorId}
                                    value={person}
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
                                            <span
                                                className={combineClassNames(
                                                    'block truncate',
                                                    selected && 'font-semibold'
                                                )}>
                                                {person.educatorName}
                                            </span>

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
