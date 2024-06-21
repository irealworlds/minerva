import { BookOpenIcon } from '@heroicons/react/24/outline';
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Transition,
} from '@headlessui/react';
import { EllipsisVerticalIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import React, { useMemo } from 'react';
import { InstitutionDisciplineViewModel } from '@/types/view-models/institution-discipline.view-model';
import { useForm } from '@inertiajs/react';
import Spinner from '@/Components/Spinner';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

interface InstitutionDisciplineEntryProps {
    institution: InstitutionViewModel;
    discipline: InstitutionDisciplineViewModel;
}

export default function InstitutionDisciplineEntry({
    institution,
    discipline,
}: InstitutionDisciplineEntryProps) {
    const { processing: deleting, delete: destroy } = useForm();

    const processing = useMemo(() => deleting, [deleting]);
    function deleteDiscipline(): void {
        if (processing) {
            throw new Error(
                'Cannot start a new operation while processing a previous one.'
            );
        }

        destroy(
            route('admin.institutions.show.disciplines.delete', {
                institution: institution.id,
                discipline: discipline.id,
            })
        );
    }

    return (
        <li className="flex justify-between gap-x-6 py-5">
            <div className="flex min-w-0 gap-x-4">
                <div className="shrink-0 size-12 rounded-full border-2 border-current flex items-center justify-center">
                    <BookOpenIcon className="size-8" />
                </div>
                <div className="min-w-0 flex-auto">
                    <p className="text-sm font-semibold leading-6 text-gray-900">
                        {discipline.abbreviation ?? discipline.name}
                    </p>
                    <p className="mt-1 flex items-center gap-2 text-xs leading-5 text-gray-500">
                        {discipline.abbreviation?.length
                            ? discipline.name
                            : 'No abbreviation'}

                        <svg
                            viewBox="0 0 2 2"
                            className="size-0.5 fill-current">
                            <circle cx={1} cy={1} r={1} />
                        </svg>

                        {discipline.addedAt &&
                            'Since ' +
                                new Date(discipline.addedAt).toLocaleDateString(
                                    undefined,
                                    {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric',
                                    }
                                )}
                    </p>
                </div>
            </div>
            <div className="flex shrink-0 items-center gap-x-6">
                {processing ? (
                    <Spinner className="size-5 text-indigo-500" />
                ) : (
                    <>
                        <Menu as="div" className="relative flex-none">
                            <MenuButton className="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
                                <span className="sr-only">Open options</span>
                                <EllipsisVerticalIcon
                                    className="size-5"
                                    aria-hidden="true"
                                />
                            </MenuButton>
                            <Transition
                                enter="transition ease-out duration-100"
                                enterFrom="transform opacity-0 scale-95"
                                enterTo="transform opacity-100 scale-100"
                                leave="transition ease-in duration-75"
                                leaveFrom="transform opacity-100 scale-100"
                                leaveTo="transform opacity-0 scale-95">
                                <MenuItems className="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                    <MenuItem>
                                        {({ focus }) => (
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    deleteDiscipline();
                                                }}
                                                className={combineClassNames(
                                                    focus ? 'bg-gray-50' : '',
                                                    'block w-full text-left px-3 py-1 text-sm leading-6 text-gray-900'
                                                )}>
                                                {deleting
                                                    ? 'Deleting'
                                                    : 'Delete'}
                                                <span className="sr-only">
                                                    , {discipline.name}
                                                </span>
                                            </button>
                                        )}
                                    </MenuItem>
                                </MenuItems>
                            </Transition>
                        </Menu>
                    </>
                )}
            </div>
        </li>
    );
}
