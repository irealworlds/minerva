import { DisciplineCreationFormData } from '@/Pages/Disciplines/Create';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import React from 'react';
import InstitutionPicture from '@/Components/Institutions/InstitutionPicture';

interface NewDisciplinePreviewProps {
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

export default function NewDisciplinePreview({
    onAdvance,
    data,
    onPreviousRequested,
    disabled,
}: NewDisciplinePreviewProps) {
    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Preview
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Your last chance to review your discipline before it is
                        created in the system.
                    </p>

                    <div className="mt-10">
                        <dl className="grid grid-cols-1 sm:grid-cols-2">
                            {/* Full name*/}
                            <div className="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
                                <dt className="text-sm font-medium leading-6 text-gray-900">
                                    Full name
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">
                                    {data.name.length > 0 ? (
                                        data.name
                                    ) : (
                                        <em className="text-gray-500">N/A</em>
                                    )}
                                </dd>
                            </div>

                            {/* Abbreviated name */}
                            <div className="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
                                <dt className="text-sm font-medium leading-6 text-gray-900">
                                    Abbreviation
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">
                                    {data.abbreviation.length > 0 ? (
                                        data.abbreviation
                                    ) : (
                                        <em className="text-gray-500">N/A</em>
                                    )}
                                </dd>
                            </div>

                            {/* Associations */}
                            <div className="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
                                <dt className="text-sm font-medium leading-6 text-gray-900">
                                    Associations
                                </dt>
                                <dd className="mt-2 text-sm text-gray-900">
                                    <ul
                                        role="list"
                                        className="divide-y divide-gray-100 rounded-md border border-gray-200">
                                        {data.associatedInstitutions.map(
                                            association => (
                                                <li
                                                    key={association.id}
                                                    className="flex items-center py-4 pl-4 pr-5 text-sm leading-6">
                                                    <InstitutionPicture
                                                        uri={
                                                            association.pictureUri
                                                        }
                                                        className="size-8 shrink-0"
                                                        aria-hidden="true"
                                                    />
                                                    <div className="ml-4">
                                                        {association.ancestors
                                                            .length > 0 && (
                                                            <ol className="mt-1 flex flex-wrap items-center gap-x-2 text-xs leading-5 text-gray-500">
                                                                {association.ancestors.map(
                                                                    ancestor => (
                                                                        <li
                                                                            key={
                                                                                ancestor.id
                                                                            }
                                                                            className="flex items-center gap-x-2">
                                                                            <p className="truncate">
                                                                                {
                                                                                    ancestor.name
                                                                                }
                                                                            </p>
                                                                            <svg
                                                                                viewBox="0 0 2 2"
                                                                                className="size-0.5 fill-current">
                                                                                <circle
                                                                                    cx={
                                                                                        1
                                                                                    }
                                                                                    cy={
                                                                                        1
                                                                                    }
                                                                                    r={
                                                                                        1
                                                                                    }
                                                                                />
                                                                            </svg>
                                                                        </li>
                                                                    )
                                                                )}
                                                            </ol>
                                                        )}
                                                        <div className="flex min-w-0 flex-1 gap-2">
                                                            <span className="truncate font-medium">
                                                                {
                                                                    association.name
                                                                }
                                                            </span>
                                                            <span className="flex-shrink-0 text-gray-400">
                                                                Institution
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            )
                                        )}
                                    </ul>
                                </dd>
                            </div>
                        </dl>
                    </div>
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
                    type="button"
                    onClick={() => {
                        onAdvance();
                    }}>
                    Create discipline
                </PrimaryButton>
            </div>
        </>
    );
}
