import { DisciplineCreationFormData } from '@/Pages/Disciplines/Create';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import {
    ArrowRightIcon,
    InformationCircleIcon,
} from '@heroicons/react/20/solid';
import React from 'react';
import { Link } from '@inertiajs/react';
import { TrashIcon } from '@heroicons/react/24/outline';
import DisciplineAssociationSelector from '@/Pages/Disciplines/Components/DisciplineAssociationSelector';

interface NewDisciplineAssociationsProps {
    data: DisciplineCreationFormData;
    setData: <K extends keyof DisciplineCreationFormData>(
        key: K,
        value: DisciplineCreationFormData[K]
    ) => void;
    errors: Partial<Record<keyof DisciplineCreationFormData, string>>;
    onAdvance: () => void;
    onPreviousRequested?: () => void;
    disabled?: boolean;
}

export default function NewDisciplineAssociations({
    onAdvance,
    data,
    setData,
    onPreviousRequested,
    disabled,
}: NewDisciplineAssociationsProps) {
    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Associations
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Institutions which will be offering this discipline as
                        part of their curriculum.
                    </p>
                    <div className="rounded-md bg-blue-50 p-4 mt-1">
                        <div className="flex">
                            <div className="flex-shrink-0">
                                <InformationCircleIcon
                                    className="h-5 w-5 text-blue-400"
                                    aria-hidden="true"
                                />
                            </div>
                            <div className="ml-3 flex-1 md:flex md:justify-between">
                                <p className="text-sm text-blue-700">
                                    Any institution will be able to add this
                                    discipline to their offer once it is
                                    created.
                                </p>
                            </div>
                        </div>
                    </div>

                    <ul role="list" className="mt-10 divide-y divide-gray-100">
                        <DisciplineAssociationSelector
                            onAdd={association => {
                                setData('associatedInstitutions', [
                                    association,
                                    ...data.associatedInstitutions.filter(
                                        a => a.id !== association.id
                                    ),
                                ]);
                            }}
                        />

                        {data.associatedInstitutions.map(association => (
                            <li
                                key={association.id}
                                className="flex items-center justify-between gap-x-6 py-5">
                                <div className="min-w-0">
                                    {association.ancestors.length > 0 && (
                                        <ol className="mt-1 flex flex-wrap items-center gap-x-2 text-xs leading-5 text-gray-500">
                                            {association.ancestors.map(
                                                ancestor => (
                                                    <li
                                                        key={ancestor.id}
                                                        className="flex items-center gap-x-2">
                                                        <p className="truncate">
                                                            {ancestor.name}
                                                        </p>
                                                        <svg
                                                            viewBox="0 0 2 2"
                                                            className="size-0.5 fill-current">
                                                            <circle
                                                                cx={1}
                                                                cy={1}
                                                                r={1}
                                                            />
                                                        </svg>
                                                    </li>
                                                )
                                            )}
                                        </ol>
                                    )}
                                    <div className="flex items-start gap-x-3">
                                        <p className="text-sm font-semibold leading-6 text-gray-900">
                                            {association.name}
                                        </p>
                                    </div>
                                    <div className="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                        <p className="whitespace-nowrap">
                                            Institution
                                        </p>
                                        {!!association.website?.length && (
                                            <>
                                                <svg
                                                    viewBox="0 0 2 2"
                                                    className="size-0.5 fill-current">
                                                    <circle
                                                        cx={1}
                                                        cy={1}
                                                        r={1}
                                                    />
                                                </svg>
                                                <Link
                                                    href={association.website}>
                                                    <p className="truncate">
                                                        {association.website}
                                                    </p>
                                                </Link>
                                            </>
                                        )}
                                    </div>
                                </div>
                                <div className="flex flex-none items-center gap-x-4">
                                    <Link
                                        href={route('institutions.show', {
                                            institution: association.id,
                                        })}
                                        className="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">
                                        View details
                                        <span className="sr-only">
                                            , {association.name}
                                        </span>
                                    </Link>

                                    <button
                                        type="button"
                                        onClick={() => {
                                            setData(
                                                'associatedInstitutions',
                                                data.associatedInstitutions.filter(
                                                    associatedInstitution =>
                                                        associatedInstitution.id !==
                                                        association.id
                                                )
                                            );
                                        }}
                                        className="relative flex size-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <span className="absolute -inset-1.5" />
                                        <TrashIcon
                                            className="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        <span className="sr-only">
                                            Remove association
                                        </span>
                                    </button>
                                </div>
                            </li>
                        ))}
                    </ul>
                </div>
            </div>

            <div className="mt-6 flex items-center justify-end gap-x-3">
                {onPreviousRequested && (
                    <SecondaryButton
                        disabled={disabled}
                        type="button"
                        onClick={() => {
                            onPreviousRequested();
                        }}>
                        Back
                    </SecondaryButton>
                )}
                <PrimaryButton
                    disabled={disabled}
                    type="submit"
                    onClick={() => {
                        onAdvance();
                    }}>
                    Advance
                    <ArrowRightIcon className="size-4 ml-2" />
                </PrimaryButton>
            </div>
        </>
    );
}
