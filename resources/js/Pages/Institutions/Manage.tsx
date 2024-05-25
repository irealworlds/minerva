import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/ViewModels/institution.view-model';
import ManageInstitutionLayout from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import { JSX } from 'react';
import ManageInstitutionDetails from '@/Pages/Institutions/Partials/Tabs/ManageInstitutionDetails';

export default function Manage({
  auth,
  institution,
  activeTab,
}: PageProps<{ institution: InstitutionViewModel; activeTab: string }>) {
  let tabContent: JSX.Element;
  switch (activeTab.toLowerCase()) {
    case 'general': {
      tabContent = (
        <ManageInstitutionDetails user={auth.user} institution={institution} />
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

      <ManageInstitutionLayout institution={institution} activeTab={activeTab}>
        {tabContent}
      </ManageInstitutionLayout>
    </AuthenticatedLayout>
  );
}
