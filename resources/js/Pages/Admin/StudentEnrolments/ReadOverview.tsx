import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import ReadEnrolmentLayout from '@/Pages/Admin/StudentEnrolments/Partials/Read/ReadEnrolmentLayout';
import { StudentEnrolmentDetailsViewModel } from '@/types/view-models/student-enrolment-details.view-model';
import React from 'react';
import StudentRegistrationId from '@/Components/Students/StudentRegistrationId';

export default function ReadOverview({
    auth,
    enrolment,
}: PageProps<{
    enrolment: StudentEnrolmentDetailsViewModel;
}>) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Overview" />

            <ReadEnrolmentLayout enrolment={enrolment}>
                <div className="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div className="px-4 py-6 sm:px-6">
                        <h3 className="text-base font-semibold leading-7 text-gray-900">
                            Student information
                        </h3>
                        <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                            Personal details and information about this
                            student's enrolment in student group{' '}
                            <span className="font-semibold">
                                {enrolment.studentGroupName}
                            </span>
                            .
                        </p>
                    </div>
                    <div className="border-t border-gray-100">
                        <dl className="divide-y divide-gray-100">
                            {/* Student registration id */}
                            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt className="text-sm font-medium text-gray-900">
                                    Student id
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <StudentRegistrationId>
                                        {enrolment.studentRegistrationId}
                                    </StudentRegistrationId>
                                </dd>
                            </div>

                            {/* Student name */}
                            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt className="text-sm font-medium text-gray-900">
                                    Full name
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {enrolment.studentName}
                                </dd>
                            </div>

                            {/* Institution */}
                            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt className="text-sm font-medium text-gray-900">
                                    Institution
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <nav className="truncate text-gray-400">
                                        <ol className="flex flex-wrap items-center gap-x-1">
                                            {enrolment.parentInstitutionAncestors.map(
                                                ancestor => (
                                                    <li
                                                        key={ancestor.id}
                                                        className="flex items-center">
                                                        <Link
                                                            href={route(
                                                                'admin.institutions.show.general',
                                                                {
                                                                    institution:
                                                                        ancestor.id,
                                                                }
                                                            )}
                                                            className="mr-1 text-xs font-medium hover:text-gray-500">
                                                            {ancestor.name}
                                                        </Link>
                                                        <svg
                                                            className="size-3 flex-shrink-0"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                            aria-hidden="true">
                                                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                                        </svg>
                                                    </li>
                                                )
                                            )}
                                        </ol>
                                    </nav>
                                    <Link
                                        href={route(
                                            'admin.institutions.show.general',
                                            {
                                                institution:
                                                    enrolment.parentInstitutionId,
                                            }
                                        )}
                                        className="text-sm leading-6 text-indigo-600 hover:text-indigo-500">
                                        {enrolment.parentInstitutionName}
                                    </Link>
                                </dd>
                            </div>

                            {/* Student group */}
                            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt className="text-sm font-medium text-gray-900">
                                    Student group
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <nav className="truncate text-gray-400">
                                        <ol className="flex flex-wrap items-center gap-x-1">
                                            {enrolment.studentGroupAncestors.map(
                                                ancestor => (
                                                    <li
                                                        key={ancestor.id}
                                                        className="flex items-center">
                                                        <span className="mr-1 text-xs font-medium">
                                                            {ancestor.name}
                                                        </span>
                                                        <svg
                                                            className="size-3 flex-shrink-0"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                            aria-hidden="true">
                                                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                                        </svg>
                                                    </li>
                                                )
                                            )}
                                        </ol>
                                    </nav>
                                    <Link
                                        href={route(
                                            'admin.institutions.show.groups',
                                            {
                                                institution:
                                                    enrolment.parentInstitutionId,
                                            }
                                        )}
                                        className="text-sm leading-6 text-indigo-600 hover:text-indigo-500">
                                        {enrolment.studentGroupName}
                                    </Link>
                                </dd>
                            </div>

                            {/* Disciplines count */}
                            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt className="text-sm font-medium text-gray-900">
                                    Studied disciplines
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <span className="">
                                        {enrolment.enroledDisciplineCount}
                                    </span>{' '}
                                    <span className="text-gray-400">
                                        out of{' '}
                                        {enrolment.studentGroupDisciplineCount}{' '}
                                        offered
                                    </span>
                                </dd>
                            </div>

                            {/* Enrolment date */}
                            <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt className="text-sm font-medium text-gray-900">
                                    Enrolment date
                                </dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {new Date(
                                        enrolment.enroledAt
                                    ).toLocaleDateString(undefined, {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric',
                                    })}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </ReadEnrolmentLayout>
        </AuthenticatedLayout>
    );
}
