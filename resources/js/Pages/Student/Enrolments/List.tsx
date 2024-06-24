import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import EnrolmentsList from '@/Pages/Student/Enrolments/Partials/List/EnrolmentsList';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import NoEnrolments from '@/Pages/Student/Enrolments/Partials/List/NoEnrolments';

type ListPageProps = PageProps<{
    enrolments: PaginatedCollection<StudentGroupEnrolmentViewModel>;
}>;

export default function List({ auth, enrolments }: ListPageProps) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="My enrolments" />

            <div className="border-b border-gray-200 pb-5 sm:flex sm:items-center sm:justify-between">
                <h3 className="text-base font-semibold leading-6 text-gray-900">
                    My enrolments
                </h3>
            </div>

            <div className="mt-6">
                {enrolments.total === 0 ? (
                    <NoEnrolments />
                ) : (
                    <EnrolmentsList enrolments={enrolments.data} />
                )}
            </div>
        </AuthenticatedLayout>
    );
}
