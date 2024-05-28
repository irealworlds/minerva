import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import {
    Dialog,
    DialogPanel,
    Transition,
    TransitionChild,
} from '@headlessui/react';
import {
    BuildingLibraryIcon,
    PencilIcon,
    UserGroupIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import DangerButton from '@/Components/Buttons/DangerButton';
import React, { useContext, useMemo } from 'react';
import { AuthenticatedContext } from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Permission } from '@/types/permission.enum';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { useForm } from '@inertiajs/react';

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

interface StudentGroupDetailsOverlayProps {
    group: StudentGroupViewModel | null;
    open: boolean;
    onClose: () => void;
}

export default function StudentGroupDetailsOverlay({
    group,
    open,
    onClose,
}: StudentGroupDetailsOverlayProps) {
    const { hasPermissions } = useContext(AuthenticatedContext);

    const { processing: deleting, delete: destroy } = useForm();

    const processing = useMemo(() => deleting, [deleting]);
    const isDeletable = useMemo(() => !group?.childrenIds.length, [group]);

    function deleteGroup(): void {
        if (processing) {
            throw new Error(
                'Cannot start a delete operation while processing another operation.'
            );
        }

        if (!group) {
            throw new Error('Student group entity not set.');
        }

        destroy(route('student_groups.delete', { group: group.id }), {
            preserveScroll: true,
            preserveState: false,
        });
    }

    return (
        <>
            <Transition show={open}>
                <Dialog className="relative z-50" onClose={onClose}>
                    {/* Background on Rest of page */}
                    <TransitionChild
                        enter="ease-in-out duration-500"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in-out duration-500"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                    </TransitionChild>

                    {/* Contents */}
                    <div className="fixed inset-0 overflow-hidden">
                        <div className="absolute inset-0 overflow-hidden">
                            <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                <TransitionChild
                                    enter="transform transition ease-in-out duration-500 sm:duration-700"
                                    enterFrom="translate-x-full"
                                    enterTo="translate-x-0"
                                    leave="transform transition ease-in-out duration-500 sm:duration-700"
                                    leaveFrom="translate-x-0"
                                    leaveTo="translate-x-full">
                                    {/* Panel */}
                                    <DialogPanel className="pointer-events-auto relative w-96">
                                        <TransitionChild
                                            enter="ease-in-out duration-500"
                                            enterFrom="opacity-0"
                                            enterTo="opacity-100"
                                            leave="ease-in-out duration-500"
                                            leaveFrom="opacity-100"
                                            leaveTo="opacity-0">
                                            {/* Close button */}
                                            <div className="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
                                                <button
                                                    type="button"
                                                    disabled={processing}
                                                    className="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                                    onClick={() => {
                                                        onClose();
                                                    }}>
                                                    <span className="absolute -inset-2.5" />
                                                    <span className="sr-only">
                                                        Close panel
                                                    </span>
                                                    <XMarkIcon
                                                        className="h-6 w-6"
                                                        aria-hidden="true"
                                                    />
                                                </button>
                                            </div>
                                        </TransitionChild>
                                        {/* Actual slide-over contents */}
                                        {group && (
                                            <div className="h-full overflow-y-auto bg-white p-8">
                                                <div className="space-y-6 pb-16">
                                                    {/* Header */}
                                                    <div>
                                                        <div className="aspect-h-7 aspect-w-10 block w-full overflow-hidden rounded-lg">
                                                            <img
                                                                src="https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80"
                                                                alt=""
                                                                className="object-cover"
                                                            />
                                                        </div>
                                                        <div className="mt-4 flex items-start justify-between">
                                                            <div>
                                                                <h2 className="text-base font-semibold leading-6 text-gray-900">
                                                                    <span className="sr-only">
                                                                        Details
                                                                        for{' '}
                                                                    </span>
                                                                    {group.name}
                                                                </h2>
                                                                <p className="text-sm font-medium text-gray-500">
                                                                    Student
                                                                    group
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {/* Information */}
                                                    <div>
                                                        <h3 className="font-medium text-gray-900">
                                                            Information
                                                        </h3>
                                                        <dl className="mt-2 divide-y divide-gray-200 border-b border-t border-gray-200">
                                                            {/* Name */}
                                                            <div className="py-3 text-sm font-medium">
                                                                <dt className="text-gray-500 flex items-center justify-between gap-2">
                                                                    Name
                                                                    {hasPermissions(
                                                                        Permission.StudentGroupUpdate
                                                                    ) && (
                                                                        <button
                                                                            type="button"
                                                                            disabled={
                                                                                processing
                                                                            }
                                                                            className="relative flex size-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                                            <span className="absolute -inset-1.5" />
                                                                            <PencilIcon
                                                                                className="h-5 w-5"
                                                                                aria-hidden="true"
                                                                            />
                                                                            <span className="sr-only">
                                                                                Change
                                                                                name
                                                                            </span>
                                                                        </button>
                                                                    )}
                                                                </dt>
                                                                <dd className="text-gray-900 mt-1">
                                                                    {group.name}
                                                                </dd>
                                                            </div>

                                                            {/* Created at */}
                                                            <div className="flex justify-between py-3 text-sm font-medium">
                                                                <dt className="text-gray-500">
                                                                    Created
                                                                </dt>
                                                                <dd className="text-gray-900">
                                                                    {new Date(
                                                                        group.createdAt
                                                                    ).toLocaleDateString(
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
                                                                <dt className="text-gray-500">
                                                                    Last
                                                                    modified
                                                                </dt>
                                                                <dd className="text-gray-900">
                                                                    {new Date(
                                                                        group.updatedAt
                                                                    ).toLocaleDateString(
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
                                                            {buildAncestorStructure(
                                                                [
                                                                    ...group.ancestors,
                                                                    {
                                                                        id: 'current',
                                                                        name: group.name,
                                                                        type: 'studentGroup',
                                                                    },
                                                                ]
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Actions */}
                                                    <div className="flex items-center gap-3">
                                                        {/*<PrimaryButton*/}
                                                        {/*    type="button"*/}
                                                        {/*    disabled={processing}*/}
                                                        {/*    className="grow justify-center">*/}
                                                        {/*    Save changes*/}
                                                        {/*</PrimaryButton>*/}
                                                        {hasPermissions(
                                                            Permission.StudentGroupDelete
                                                        ) && (
                                                            <DangerButton
                                                                type="button"
                                                                onClick={() => {
                                                                    deleteGroup();
                                                                }}
                                                                disabled={
                                                                    processing ||
                                                                    !isDeletable
                                                                }
                                                                className="grow justify-center">
                                                                Delete
                                                            </DangerButton>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </DialogPanel>
                                </TransitionChild>
                            </div>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    );
}
