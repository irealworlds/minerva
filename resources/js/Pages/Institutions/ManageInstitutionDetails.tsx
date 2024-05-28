import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import React from 'react';
import UpdateInstitutionPublicDetailsForm from '@/Pages/Institutions/Partials/UpdateInstitutionPublicDetailsForm';
import DeleteInstitutionForm from '@/Pages/Institutions/Partials/DeleteInstitutionForm';
import { checkPermissionsForUser } from '@/utils/access-control/has-permission.function';
import { Permission } from '@/types/permission.enum';
import { PageProps } from '@/types';
import ManageInstitutionLayout from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function ManageInstitutionDetails({
    auth,
    institution,
}: PageProps<{
    institution: InstitutionViewModel;
}>) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Institution management" />
            <ManageInstitutionLayout institution={institution}>
                <div className="space-y-12">
                    {/* Public details */}
                    <UpdateInstitutionPublicDetailsForm
                        institution={institution}
                    />

                    {/* Deletion */}
                    {checkPermissionsForUser(
                        auth.user,
                        Permission.InstitutionsDelete
                    ) && <DeleteInstitutionForm institution={institution} />}
                </div>
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
