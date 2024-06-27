import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { StudentEnrolmentDetailsViewModel } from '@/types/view-models/student-enrolment-details.view-model';
import React, { useState } from 'react';
import ReadEnrolmentLayout from '@/Pages/Admin/StudentEnrolments/Partials/Read/ReadEnrolmentLayout';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/admin/student-discipline-enrolment.view-model';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { PlusIcon } from '@heroicons/react/20/solid';
import NoDisciplines from '@/Pages/Admin/StudentEnrolments/Partials/Read/Disciplines/NoDisciplines';
import DisciplinesList from '@/Pages/Admin/StudentEnrolments/Partials/Read/Disciplines/DisciplinesList';
import AddDisciplineDialog from '@/Pages/Admin/StudentEnrolments/Partials/Read/Disciplines/Add/AddDisciplineDialog';

type ReadDisciplinesPageProps = PageProps<{
    enrolment: StudentEnrolmentDetailsViewModel;
    disciplines: PaginatedCollection<StudentDisciplineEnrolmentViewModel>;
}>;

export default function ReadDisciplines({
    auth,
    enrolment,
    disciplines,
}: ReadDisciplinesPageProps) {
    const [newDisciplineDialogOpen, setNewDisciplineDialogOpen] =
        useState(false);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Disciplines" />

            <ReadEnrolmentLayout enrolment={enrolment}>
                <AddDisciplineDialog
                    enrolment={enrolment}
                    open={newDisciplineDialogOpen}
                    onClose={() => {
                        setNewDisciplineDialogOpen(false);
                    }}
                />

                <div className="border-b border-gray-200 pb-5 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h3 className="text-base font-semibold leading-6 text-gray-900">
                            Disciplines
                        </h3>
                        <p className="mt-2 max-w-4xl text-sm text-gray-500">
                            A list of disciplines that the student is enrolled
                            in as part of their being enroled in student group{' '}
                            {enrolment.studentGroupName}.
                        </p>
                    </div>
                    <div className="mt-3 sm:ml-4 sm:mt-0 shrink-0">
                        <PrimaryButton
                            type="button"
                            onClick={() => {
                                setNewDisciplineDialogOpen(true);
                            }}>
                            <PlusIcon className="size-4 mr-2" />
                            Add discipline
                        </PrimaryButton>
                    </div>
                </div>

                <div className="mt-6">
                    {disciplines.total === 0 ? (
                        <NoDisciplines
                            addDiscipline={() => {
                                setNewDisciplineDialogOpen(true);
                            }}
                        />
                    ) : (
                        <DisciplinesList disciplines={disciplines.data} />
                    )}
                </div>
            </ReadEnrolmentLayout>
        </AuthenticatedLayout>
    );
}
