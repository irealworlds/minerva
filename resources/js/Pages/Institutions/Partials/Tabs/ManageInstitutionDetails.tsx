import { InstitutionViewModel } from '@/types/ViewModels/institution.view-model';
import React from 'react';
import UpdateInstitutionPublicDetailsForm from '@/Pages/Institutions/Partials/UpdateInstitutionPublicDetailsForm';
import DeleteInstitutionForm from '@/Pages/Institutions/Partials/DeleteInstitutionForm';
import { hasPermission } from '@/utils/access-control/has-permission.function';
import { Permission } from '@/types/permission.enum';
import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';

export default function ManageInstitutionDetails({
  user,
  institution,
}: {
  user: AuthenticatedUserViewModel;
  institution: InstitutionViewModel;
}) {
  return (
    <>
      <div className="space-y-12">
        <UpdateInstitutionPublicDetailsForm institution={institution} />
        {hasPermission(user, Permission.InstitutionsDelete) && (
          <DeleteInstitutionForm institution={institution} />
        )}
      </div>
    </>
  );
}
