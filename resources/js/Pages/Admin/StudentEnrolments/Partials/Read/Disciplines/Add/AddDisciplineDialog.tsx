import {
    Dialog,
    DialogBackdrop,
    DialogPanel,
    DialogTitle,
} from '@headlessui/react';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { AcademicCapIcon } from '@heroicons/react/24/outline';
import { StudentEnrolmentDetailsViewModel } from '@/types/view-models/student-enrolment-details.view-model';
import DisciplineSelector from '@/Pages/Admin/StudentEnrolments/Partials/Read/Disciplines/Add/DisciplineSelector';
import { FormEventHandler, useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import { StudentGroupDisciplineDto } from '@/types/dtos/student-group-discipline.dto';
import EducatorSelector from '@/Pages/Admin/StudentEnrolments/Partials/Read/Disciplines/Add/EducatorSelector';

interface AddDisciplineDialogProps {
    enrolment: StudentEnrolmentDetailsViewModel;

    open: boolean;
    onClose: () => void;
}

interface NewDisciplineData {
    disciplineKey: string;
    educatorKey: string;
}

export default function AddDisciplineDialog({
    enrolment,
    open,
    onClose,
}: AddDisciplineDialogProps) {
    const [selectedDiscipline, setSelectedDiscipline] =
        useState<StudentGroupDisciplineDto | null>(null);
    const [selectedEducator, setSelectedEducator] = useState<{
        educatorId: string;
        educatorName: string;
    } | null>(null);

    const { setData, processing, post } = useForm<NewDisciplineData>({
        disciplineKey: '',
        educatorKey: '',
    });

    useEffect(() => {
        setData(previousData => ({
            ...previousData,
            disciplineKey: selectedDiscipline?.id ?? '',
        }));
    }, [selectedDiscipline]);

    useEffect(() => {
        setData(previousData => ({
            ...previousData,
            educatorKey: selectedEducator?.educatorId ?? '',
        }));
    }, [selectedEducator]);

    const submit: FormEventHandler = event => {
        post(
            route('admin.studentGroupEnrolments.read.disciplines.store', {
                enrolment: enrolment.id,
            }),
            {
                onSuccess: () => {
                    onClose();
                },
            }
        );
        event.preventDefault();
    };

    return (
        <Dialog
            className="relative z-50"
            open={open}
            onClose={() => {
                onClose();
            }}>
            <DialogBackdrop
                transition
                className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in"
            />

            <div className="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <DialogPanel
                        transition
                        className="relative transform rounded-lg bg-white text-left shadow-xl transition-all data-[closed]:translate-y-4 data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in sm:my-8 sm:w-full sm:max-w-2xl data-[closed]:sm:translate-y-0 data-[closed]:sm:scale-95">
                        <form onSubmit={submit}>
                            <div className="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div className="sm:flex sm:items-start">
                                    <div className="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-900 sm:mx-0 sm:h-10 sm:w-10">
                                        <AcademicCapIcon
                                            className="size-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div className="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <DialogTitle
                                            as="h3"
                                            className="text-base font-semibold leading-6 text-gray-900">
                                            Add discipline
                                        </DialogTitle>
                                        <div className="mt-2">
                                            <p className="text-sm text-gray-500">
                                                Add a new discipline to this
                                                enrolment of{' '}
                                                <span className="font-medium">
                                                    {enrolment.studentName}
                                                </span>{' '}
                                                in student group{' '}
                                                <span className="font-medium">
                                                    {enrolment.studentGroupName}
                                                </span>
                                                .
                                            </p>

                                            <div className="mt-6 space-y-6">
                                                <DisciplineSelector
                                                    disabled={processing}
                                                    studentGroupKey={
                                                        enrolment.studentGroupKey
                                                    }
                                                    value={selectedDiscipline}
                                                    onChange={
                                                        setSelectedDiscipline
                                                    }
                                                />

                                                <EducatorSelector
                                                    disabled={
                                                        processing ||
                                                        !selectedDiscipline
                                                    }
                                                    educators={
                                                        selectedDiscipline?.educators ??
                                                        []
                                                    }
                                                    value={selectedEducator}
                                                    onChange={
                                                        setSelectedEducator
                                                    }
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                                <PrimaryButton
                                    disabled={processing}
                                    type="submit">
                                    {processing ? 'Saving' : 'Save'}
                                </PrimaryButton>
                                <SecondaryButton
                                    disabled={processing}
                                    onClick={() => {
                                        onClose();
                                    }}
                                    type="button"
                                    data-autofocus>
                                    Cancel
                                </SecondaryButton>
                            </div>
                        </form>
                    </DialogPanel>
                </div>
            </div>
        </Dialog>
    );
}
