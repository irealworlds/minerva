import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import ReadLayout from '@/Pages/Student/Enrolments/Partials/Read/ReadLayout';
import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/student/student-discipline-enrolment.view-model';
import Paginator from '@/Components/Paginator';
import DisciplinesList from '@/Pages/Student/Enrolments/Partials/Read/Disciplines/DisciplinesList';

type ReadDisciplinesPageProps = PageProps<{
    enrolment: StudentGroupEnrolmentViewModel;
    disciplineEnrolments: PaginatedCollection<StudentDisciplineEnrolmentViewModel>;
}>;

export default function ReadDisciplines({
    auth,
    enrolment,
    disciplineEnrolments,
}: ReadDisciplinesPageProps) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="ReadDisciplines" />

            <ReadLayout enrolment={enrolment}>
                <DisciplinesList
                    disciplineEnrolments={disciplineEnrolments.data}
                />

                <Paginator collection={disciplineEnrolments} className="mt-6" />
            </ReadLayout>
        </AuthenticatedLayout>
    );
}
