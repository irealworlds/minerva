import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import EmptyInstitutionsList from '@/Pages/Institutions/Partials/EmptyInstitutionsList';
import InstitutionsTable from '@/Pages/Institutions/Partials/InstitutionsTable';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import InstitutionFilters from '@/Pages/Institutions/Partials/InstitutionFilters';
import React from 'react';

export default function List({
    auth,
    institutions,
    filters,
}: PageProps<{
    institutions: PaginatedCollection<InstitutionViewModel>;
    filters: { search: string | null };
}>) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Institutions" />

            {institutions.total > 0 && (
                <div className="flex flex-col gap-y-3 md:flex-row md:justify-between mb-6">
                    <h2 className="text-xl">Institutions</h2>
                    <Link href={route('institutions.create')}>
                        <PrimaryButton className="w-full justify-center md:justify-start">
                            Create
                        </PrimaryButton>
                    </Link>
                </div>
            )}
            <div className="flex gap-x-12">
                <InstitutionFilters filters={filters} />

                {institutions.data.length ? (
                    <InstitutionsTable institutions={institutions} />
                ) : (
                    <EmptyInstitutionsList className="grow" />
                )}
            </div>
        </AuthenticatedLayout>
    );
}
