import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import React from 'react';
import { PageProps } from '@/types';
import ManageInstitutionLayout from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { InstitutionDisciplineViewModel } from '@/types/view-models/institution-discipline.view-model';
import NoDisciplines from '@/Pages/Admin/Institutions/Partials/Manage/Disciplines/NoDisciplines';
import InstitutionDisciplineEntry from '@/Pages/Admin/Institutions/Partials/Manage/Disciplines/InstitutionDisciplineEntry';
import AddInstitutionDiscipline from '@/Pages/Admin/Institutions/Partials/Manage/Disciplines/AddInstitutionDiscipline';

export default function ManageInstitutionDisciplines({
    auth,
    institution,
    disciplines,
}: PageProps<{
    institution: InstitutionViewModel;
    disciplines: InstitutionDisciplineViewModel[];
}>) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Institution discipline management" />
            <ManageInstitutionLayout institution={institution}>
                <div className="bg-white p-6 rounded-lg shadow">
                    <div className="border-b border-gray-900/10 pb-12">
                        <h2 className="text-base font-semibold leading-7 text-gray-900">
                            Disciplines
                        </h2>
                        <p className="mt-1 text-sm leading-6 text-gray-600">
                            A list of disciplines offered by this institution to
                            their enroled students.
                        </p>
                    </div>

                    <div className="mt-6 space-y-6">
                        <AddInstitutionDiscipline institution={institution} />
                        {disciplines.length === 0 ? (
                            <NoDisciplines institution={institution} />
                        ) : (
                            <>
                                <ul
                                    role="list"
                                    className="divide-y divide-gray-100">
                                    {disciplines.map(discipline => (
                                        <InstitutionDisciplineEntry
                                            key={discipline.id}
                                            institution={institution}
                                            discipline={discipline}
                                        />
                                    ))}
                                </ul>
                            </>
                        )}
                    </div>
                </div>
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
