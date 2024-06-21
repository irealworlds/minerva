import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import {
    Dialog,
    DialogPanel,
    Transition,
    TransitionChild,
} from '@headlessui/react';
import { XMarkIcon } from '@heroicons/react/24/outline';
import React, { useEffect, useMemo, useState } from 'react';
import { useForm } from '@inertiajs/react';
import StudentGroupReadonlyDetails from '@/Pages/Admin/Institutions/Partials/Manage/Groups/StudentGroupReadonlyDetails';
import StudentGroupUpdateInformation from '@/Pages/Admin/Institutions/Partials/Manage/Groups/StudentGroupUpdateInformation';
import StudentGroupAddDiscipline from '@/Pages/Admin/Institutions/Partials/Manage/Groups/StudentGroupAddDiscipline';

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
    const [modifyingSection, setModifyingSection] = useState<
        'information' | 'addDiscipline' | null
    >(null);
    const { processing: deleting } = useForm();

    const processing = useMemo(() => deleting, [deleting]);

    // Reset modifying section when group changes
    useEffect(() => {
        setModifyingSection(null);
    }, [group]);

    function renderSection(
        modifyingSection: 'information' | 'addDiscipline' | null
    ) {
        if (!group) throw new Error('Group not selected.');
        switch (modifyingSection) {
            case 'information':
                return (
                    <StudentGroupUpdateInformation
                        group={group}
                        setModifyingSection={setModifyingSection}
                    />
                );
            case 'addDiscipline':
                return (
                    <StudentGroupAddDiscipline
                        group={group}
                        setModifyingSection={setModifyingSection}
                    />
                );
            default:
                return (
                    <StudentGroupReadonlyDetails
                        group={group}
                        setModifyingSection={setModifyingSection}
                    />
                );
        }
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
                                                <div className="space-y-12 pb-16">
                                                    {/* Header */}
                                                    <div>
                                                        <div className="flex items-start justify-between">
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

                                                    {renderSection(
                                                        modifyingSection
                                                    )}
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
