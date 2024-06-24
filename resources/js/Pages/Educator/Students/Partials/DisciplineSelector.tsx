import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/educator/student-discipline-enrolment.view-model';

interface DisciplineSelectorProps {
    disciplines: StudentDisciplineEnrolmentViewModel[];
    value: StudentDisciplineEnrolmentViewModel | null;
    onChange: (newValue: StudentDisciplineEnrolmentViewModel | null) => void;
    className?: string;
    disabled?: boolean | undefined;
}

export default function DisciplineSelector({
    disciplines,
    value,
    onChange,
    className,
    disabled,
}: DisciplineSelectorProps) {
    return (
        <Listbox value={value} onChange={onChange}>
            {() => (
                <div className={className}>
                    <div className="relative">
                        <ListboxButton
                            disabled={disabled}
                            className="relative w-full  rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <span className="inline-flex w-full truncate">
                                <span className="truncate">
                                    {value?.disciplineAbbreviation ??
                                        value?.disciplineName ??
                                        'Select a discipline'}
                                </span>
                                <span className="ml-2 truncate text-gray-500">
                                    {value?.studentGroupName}
                                </span>
                            </span>
                            <span className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon
                                    className="h-5 w-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </span>
                        </ListboxButton>

                        <ListboxOptions className="absolute z-30 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none data-[closed]:data-[leave]:opacity-0 data-[leave]:transition data-[leave]:duration-100 data-[leave]:ease-in sm:text-sm">
                            {disciplines.map((discipline, disciplineIdx) => (
                                <ListboxOption
                                    key={disciplineIdx}
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
                                            <div
                                                className="flex"
                                                title={`${discipline.disciplineName} in student group ${discipline.studentGroupName}`}>
                                                <span
                                                    className={combineClassNames(
                                                        selected
                                                            ? 'font-semibold'
                                                            : 'font-normal',
                                                        'truncate'
                                                    )}>
                                                    {discipline.disciplineName}
                                                </span>
                                                <span
                                                    className={combineClassNames(
                                                        focus
                                                            ? 'text-indigo-200'
                                                            : 'text-gray-500',
                                                        'ml-2 truncate'
                                                    )}>
                                                    in student group{' '}
                                                    {
                                                        discipline.studentGroupName
                                                    }
                                                </span>
                                            </div>

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
