import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { DisciplineDto } from '@/types/dtos/discipline.dto';

interface DisciplineSelectorProps {
    disciplines: DisciplineDto[];
    value: DisciplineDto | null;
    onChange: (newValue: DisciplineDto | null) => void;
    className?: string;
}

export default function DisciplineSelector({
    disciplines,
    value,
    onChange,
    className,
}: DisciplineSelectorProps) {
    return (
        <Listbox value={value} onChange={onChange}>
            {() => (
                <div className={className}>
                    <div className="relative mt-2">
                        <ListboxButton className="relative w-full  rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <span className="block truncate">
                                {value?.name ?? 'Select a discipline'}
                            </span>
                            <span className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon
                                    className="h-5 w-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </span>
                        </ListboxButton>

                        <ListboxOptions className="absolute z-30 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none data-[closed]:data-[leave]:opacity-0 data-[leave]:transition data-[leave]:duration-100 data-[leave]:ease-in sm:text-sm">
                            {disciplines.map(discipline => (
                                <ListboxOption
                                    key={discipline.id}
                                    className={({ focus }) =>
                                        combineClassNames(
                                            focus
                                                ? 'bg-indigo-600 text-white'
                                                : '',
                                            !focus ? 'text-gray-900' : '',
                                            'relative cursor-pointer select-none py-2 pl-3 pr-9'
                                        )
                                    }
                                    value={discipline}>
                                    {({ selected, focus }) => (
                                        <>
                                            <span
                                                className={combineClassNames(
                                                    selected
                                                        ? 'font-semibold'
                                                        : 'font-normal',
                                                    'block truncate'
                                                )}>
                                                {discipline.name}
                                            </span>

                                            {selected ? (
                                                <span
                                                    className={combineClassNames(
                                                        focus
                                                            ? 'text-white'
                                                            : 'text-indigo-600',
                                                        'absolute inset-y-0 right-0 flex items-center pr-4'
                                                    )}>
                                                    <CheckIcon
                                                        className="h-5 w-5"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            ) : null}
                                        </>
                                    )}
                                </ListboxOption>
                            ))}
                        </ListboxOptions>
                    </div>
                </div>
            )}
        </Listbox>
    );
}
