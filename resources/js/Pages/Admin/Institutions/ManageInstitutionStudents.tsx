import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import ManageInstitutionLayout from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';
import InstitutionStudentsList from '@/Pages/Admin/Institutions/Partials/Manage/Students/InstitutionStudentsList';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { PersonalNameDto } from '@/types/dtos/personal-name.dto';

export type ManageInstitutionStudentsProps = PageProps<{
    institution: InstitutionViewModel;
    enrolments: PaginatedCollection<{
        id: string;
        name: PersonalNameDto;
        studentRegistrationId: string;
        studentGroup: string;
        createdAt: string;
    }>;
}>;

export default function ManageInstitutionStudents({
    auth,
    institution,
    enrolments,
}: ManageInstitutionStudentsProps) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Institution students management" />

            <ManageInstitutionLayout institution={institution}>
                <InstitutionStudentsList enrolments={enrolments} />
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
