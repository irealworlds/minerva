import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import ReadEducatorLayout from '@/Pages/Admin/Educators/Partials/Read/ReadEducatorLayout';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import React from 'react';
import { EducatorOverviewViewModel } from '@/types/view-models/admin/educator-overview.view-model';

type ReadOverviewPageProps = PageProps<{
    educator: EducatorOverviewViewModel;
}>;

export default function ReadOverview({
    auth,
    educator,
}: ReadOverviewPageProps) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="ReadOverview" />

            <ReadEducatorLayout educator={educator}>
                <div className="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
                    <div className="px-4 py-5 sm:px-6">
                        <div className="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                            <div className="ml-4 mt-4">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <img
                                            className="h-12 w-12 rounded-full"
                                            src={educator.pictureUri}
                                            alt=""
                                        />
                                    </div>
                                    <div className="ml-4">
                                        <h3 className="text-base font-semibold leading-6 text-gray-900">
                                            {educator.fullName}
                                        </h3>
                                        <p className="text-sm text-gray-500">
                                            <a
                                                href={`mailto:${educator.email}`}>
                                                {educator.email}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="px-4 py-5 sm:p-6">
                        <div className="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            {/* Id number */}
                            <div className="sm:col-span-4">
                                <InputLabel
                                    htmlFor="idNumber"
                                    value="Id number"
                                />

                                <TextInput
                                    id="idNumber"
                                    type="text"
                                    name="idNumber"
                                    value={educator.username}
                                    readOnly={true}
                                    className="mt-1 block w-full"
                                />
                            </div>

                            {/* Name */}
                            <div className="sm:col-span-4">
                                <InputLabel htmlFor="name" value="Full name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    name="name"
                                    value={educator.fullName}
                                    readOnly={true}
                                    className="mt-1 block w-full"
                                />
                            </div>

                            {/* E-mail address */}
                            <div className="sm:col-span-4">
                                <InputLabel
                                    htmlFor="email"
                                    value="E-mail address"
                                />

                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={educator.email}
                                    readOnly={true}
                                    className="mt-1 block w-full"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </ReadEducatorLayout>
        </AuthenticatedLayout>
    );
}
