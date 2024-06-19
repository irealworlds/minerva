import React, { useContext } from 'react';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { UserPlusIcon } from '@heroicons/react/24/outline';
import { PersonalNameDto } from '@/types/dtos/personal-name.dto';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import Paginator from '@/Components/Paginator';
import { InstitutionManagementContext } from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import StudentRegistrationId from '@/Components/Students/StudentRegistrationId';

interface InstitutionStudentsTableProps {
    enrolments: PaginatedCollection<{
        id: string;
        name: PersonalNameDto;
        studentRegistrationId: string;
        studentGroup: string;
        createdAt: string;
    }>;
}

export default function InstitutionStudentsTable({
    enrolments,
}: InstitutionStudentsTableProps) {
    const { institution } = useContext(InstitutionManagementContext);

    return (
        <div className="px-4 sm:px-6 lg:px-8 bg-white p-6 rounded-lg shadow">
            <div className="sm:flex sm:items-center">
                <div className="sm:flex-auto">
                    <h1 className="text-base font-semibold leading-6 text-gray-900">
                        Student enrolments
                    </h1>
                    <p className="mt-2 text-sm text-gray-700">
                        A list of students enroled in on of this institution's
                        student groups.
                    </p>
                </div>
                <div className="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                    <a
                        href={route('student_enrolments.create', {
                            institutionKey: institution?.id,
                        })}>
                        <PrimaryButton type="button">
                            <UserPlusIcon className="size-5 mr-2" />
                            New enrolment
                        </PrimaryButton>
                    </a>
                </div>
            </div>
            <div className="mt-8 flow-root">
                <div className="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div className="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table className="min-w-full divide-y divide-gray-300">
                            <thead>
                                <tr>
                                    <th
                                        scope="col"
                                        className="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        className="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Student registration id
                                    </th>
                                    <th
                                        scope="col"
                                        className="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Student group
                                    </th>
                                    <th
                                        scope="col"
                                        className="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-0">
                                        <span className="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200 bg-white">
                                {enrolments.data.map(enrolment => (
                                    <tr key={enrolment.id}>
                                        <td className="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                                            <span className="font-semibold">
                                                {enrolment.name.lastName.toUpperCase()}
                                            </span>
                                            , {enrolment.name.firstName}
                                        </td>
                                        <td className="py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                            <StudentRegistrationId className="truncate text-left">
                                                {
                                                    enrolment.studentRegistrationId
                                                }
                                            </StudentRegistrationId>
                                        </td>
                                        <td className="py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                            {enrolment.studentGroup}
                                        </td>
                                        <td className="relative py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <a
                                                href={route(
                                                    'student_enrolments.manage',
                                                    { enrolment: enrolment.id }
                                                )}
                                                className="text-indigo-600 hover:text-indigo-900">
                                                Manage
                                                <span className="sr-only">
                                                    enrolment for{' '}
                                                    {enrolment.name.firstName}{' '}
                                                    {enrolment.name.lastName}
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <Paginator className="mt-4" collection={enrolments} />
        </div>
    );
}
