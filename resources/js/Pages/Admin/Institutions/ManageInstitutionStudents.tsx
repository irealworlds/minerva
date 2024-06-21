import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import ManageInstitutionLayout from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';
import InstitutionStudentsTable from '@/Pages/Admin/Institutions/Partials/Manage/Students/InstitutionStudentsTable';
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
                <InstitutionStudentsTable enrolments={enrolments} />
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
