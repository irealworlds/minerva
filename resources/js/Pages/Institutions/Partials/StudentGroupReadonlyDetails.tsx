import { Permission } from '@/types/permission.enum';
import {
    BuildingLibraryIcon,
    PencilIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';
import DangerButton from '@/Components/Buttons/DangerButton';
import React, { useContext, useMemo } from 'react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import { AuthenticatedContext } from '@/Layouts/Authenticated/AuthenticatedLayout';
import { useForm } from '@inertiajs/react';
import { combineClassNames } from '@/utils/combine-class-names.function';

function AncestorPicture({
    ancestor,
}: {
    ancestor: {
        id: string;
        type: 'institution' | 'studentGroup';
        name: string;
    };
}) {
    if ('pictureUri' in ancestor && typeof ancestor.pictureUri === 'string') {
        return (
            <img
                src={ancestor.pictureUri}
                alt=""
                className="size-8 rounded-full"
            />
        );
    } else if (ancestor.type === 'institution') {
        return (
            <div
                className="shrink-0 size-8 border border-current flex items-center justify-center rounded-full text-current"
                aria-hidden="true">
                <BuildingLibraryIcon className="size-6" />
            </div>
        );
    } else {
        return (
            <div
                className="shrink-0 size-8 border border-current flex items-center justify-center rounded-full text-current"
                aria-hidden="true">
                <UserGroupIcon className="size-6" />
            </div>
        );
    }
}

function buildAncestorStructure(
    ancestors: {
        id: string;
        type: 'institution' | 'studentGroup';
        name: string;
    }[]
) {
    if (ancestors.length > 0) {
        return (
            <ol>
                <li
                    key={ancestors[0].id}
                    className={combineClassNames(
                        'py-3 text-sm font-medium flex items-center',
                        ancestors.length === 1
                            ? 'font-bold bg-gray-100 px-4 rounded-lg text-indigo-600'
                            : 'text-gray-900'
                    )}>
                    <AncestorPicture ancestor={ancestors[0]} />
                    <p className="ml-4 text-sm font-medium">
                        {ancestors[0].name}
                    </p>
                </li>
                <li className="pl-5 border-l">
                    {buildAncestorStructure(ancestors.slice(1))}
                </li>
            </ol>
        );
    } else {
        return <></>;
    }
}

interface StudentGroupReadonlyDetailsProps {
    group: StudentGroupViewModel;
    setModifyingSection: (section: 'information' | null) => void;
}

export default function StudentGroupReadonlyDetails({
    group,
    setModifyingSection,
}: StudentGroupReadonlyDetailsProps) {
    const { hasPermissions } = useContext(AuthenticatedContext);

    const { processing, delete: destroy } = useForm();
    const isDeletable = useMemo(() => !group.childrenIds.length, [group]);

    function deleteGroup(): void {
        if (processing) {
            throw new Error(
                'Cannot start a delete operation while processing another operation.'
            );
        }

        destroy(route('student_groups.delete', { group: group.id }), {
            preserveScroll: true,
            preserveState: false,
        });
    }

    return (
        <>
            {/* Information */}
            <div>
                <div className="flex items-center justify-between gap-2">
                    <h3 className="font-medium text-gray-900">Information</h3>

                    {hasPermissions(Permission.StudentGroupUpdate) && (
                        <button
                            type="button"
                            onClick={() => {
                                setModifyingSection('information');
                            }}
                            disabled={processing}
                            className="relative flex size-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span className="absolute -inset-1.5" />
                            <PencilIcon
                                className="h-5 w-5"
                                aria-hidden="true"
                            />
                            <span className="sr-only">Change name</span>
                        </button>
                    )}
                </div>
                <dl className="mt-2 divide-y divide-gray-200 border-b border-t border-gray-200">
                    {/* Name */}
                    <div className="flex justify-between gap-6 py-3 text-sm font-medium">
                        <dt className="text-gray-500">Name</dt>
                        <dd className="text-gray-900">{group.name}</dd>
                    </div>

                    {/* Created at */}
                    <div className="flex justify-between py-3 text-sm font-medium">
                        <dt className="text-gray-500">Created</dt>
                        <dd className="text-gray-900">
                            {new Date(group.createdAt).toLocaleDateString(
                                undefined,
                                {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric',
                                }
                            )}
                        </dd>
                    </div>

                    {/* Updated at */}
                    <div className="flex justify-between py-3 text-sm font-medium">
                        <dt className="text-gray-500">Last modified</dt>
                        <dd className="text-gray-900">
                            {new Date(group.updatedAt).toLocaleDateString(
                                undefined,
                                {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric',
                                }
                            )}
                        </dd>
                    </div>
                </dl>
            </div>

            {/* Structure */}
            <div>
                <h3 className="font-medium text-gray-900">
                    Place in structure
                </h3>
                <div className="mt-2 border-t border-gray-200">
                    {buildAncestorStructure([
                        ...group.ancestors,
                        {
                            id: 'current',
                            name: group.name,
                            type: 'studentGroup',
                        },
                    ])}
                </div>
            </div>

            {/* Actions */}
            <div className="flex items-center gap-3">
                {hasPermissions(Permission.StudentGroupDelete) && (
                    <DangerButton
                        type="button"
                        onClick={() => {
                            deleteGroup();
                        }}
                        disabled={processing || !isDeletable}
                        className="grow justify-center">
                        Delete
                    </DangerButton>
                )}
            </div>
        </>
    );
}
