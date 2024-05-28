import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import ManageInstitutionLayout from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import { JSX } from 'react';
import ManageInstitutionDetails from '@/Pages/Institutions/Partials/Tabs/ManageInstitutionDetails';
import ManageInstitutionGroups from '@/Pages/Institutions/Partials/Tabs/ManageInstitutionGroups';
import { StudentGroupTreeViewModel } from '@/types/view-models/student-group-tree.view-model';

export default function Manage({
    auth,
    institution,
    activeTab,
    groups,
}: PageProps<{
    institution: InstitutionViewModel;
    activeTab: string;
    groups: StudentGroupTreeViewModel | null;
}>) {
    let tabContent: JSX.Element;
    switch (activeTab.toLowerCase()) {
        case 'general': {
            tabContent = (
                <ManageInstitutionDetails
                    user={auth.user}
                    institution={institution}
                />
            );
            break;
        }
        case 'groups': {
            tabContent = (
                <ManageInstitutionGroups
                    user={auth.user}
                    institution={institution}
                    groups={groups}
                />
            );
            break;
        }
        default: {
            router.visit(
                route('institutions.show', {
                    institution: institution.id,
                    tab: 'General',
                })
            );
            return;
        }
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Manage" />

            <ManageInstitutionLayout
                institution={institution}
                activeTab={activeTab}>
                {tabContent}
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
