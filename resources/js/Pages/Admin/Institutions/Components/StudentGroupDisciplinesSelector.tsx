import { useEffect, useState } from 'react';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { fetchAllPages } from '@/utils/pagination/get-all-pages.function';
import {
    Label,
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
    Transition,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface StudentGroupDisciplinesSelectorProps {
    studentGroupId: string;
    onChange: (disciplines: DisciplineDto[]) => void;
}

export default function StudentGroupDisciplinesSelector({
    studentGroupId,
    onChange,
}: StudentGroupDisciplinesSelectorProps) {
    const [disciplinesList, setDisciplinesList] = useState<
        DisciplineDto[] | undefined
    >(undefined);
    const [selectedDisciplines, setSelectedDisciplines] = useState<
        DisciplineDto[]
    >([]);

    useEffect(() => {
        fetchAllPages(
            route('api.admin.disciplines.index', {
                associatedToStudentGroupIds: studentGroupId,
            }),
            (response: { disciplines: PaginatedCollection<DisciplineDto> }) =>
                response.disciplines
        ).then(
            disciplines => {
                setDisciplinesList(disciplines);
            },
            () => {
                // Do nothing
            }
        );
    }, [studentGroupId]);

    useEffect(() => {
        onChange(selectedDisciplines);
    }, [selectedDisciplines]);

    return (
        <div>
            {disciplinesList && (
                <Listbox
                    value={selectedDisciplines}
                    onChange={setSelectedDisciplines}
                    multiple={true}>
                    {({ open }) => (
                        <>
                            <Label className="block text-sm font-medium leading-6 text-gray-900">
                                Disciplines
                            </Label>
                            <div className="relative mt-2">
                                <ListboxButton className="relative w-full rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <span className="block truncate">
                                        {selectedDisciplines.length === 0
                                            ? 'None selected'
                                            : selectedDisciplines
                                                  .map(
                                                      d =>
                                                          d.abbreviation ??
                                                          d.name
                                                  )
                                                  .join(', ')}
                                    </span>
                                    <span className="absolute cursor-pointer inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon
                                            className="size-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </ListboxButton>

                                <Transition
                                    show={open}
                                    leave="transition ease-in duration-100"
                                    leaveFrom="opacity-100"
                                    leaveTo="opacity-0">
                                    <ListboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                        {disciplinesList.map(person => (
                                            <ListboxOption
                                                key={person.id}
                                                className={({ focus }) =>
                                                    combineClassNames(
                                                        focus
                                                            ? 'bg-indigo-600 text-white'
                                                            : '',
                                                        !focus
                                                            ? 'text-gray-900'
                                                            : '',
                                                        'relative cursor-pointer select-none py-2 pl-8 pr-4'
                                                    )
                                                }
                                                value={person}>
                                                {({ selected, focus }) => (
                                                    <>
                                                        <span
                                                            className={combineClassNames(
                                                                selected
                                                                    ? 'font-semibold'
                                                                    : 'font-normal',
                                                                'block truncate'
                                                            )}>
                                                            {person.name}
                                                        </span>

                                                        {selected ? (
                                                            <span
                                                                className={combineClassNames(
                                                                    focus
                                                                        ? 'text-white'
                                                                        : 'text-indigo-600',
                                                                    'absolute inset-y-0 left-0 flex items-center pl-1.5'
                                                                )}>
                                                                <CheckIcon
                                                                    className="size-5"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        ) : null}
                                                    </>
                                                )}
                                            </ListboxOption>
                                        ))}
                                    </ListboxOptions>
                                </Transition>
                            </div>
                        </>
                    )}
                </Listbox>
            )}
        </div>
    );
}
