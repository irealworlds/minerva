import StudentRegistrationId from '@/Components/Students/StudentRegistrationId';
import React from 'react';
import { PersonalNameDto } from '@/types/dtos/personal-name.dto';

interface InstitutionStudentsTableProps {
    enrolments: {
        id: string;
        name: PersonalNameDto;
        studentRegistrationId: string;
        studentGroup: string;
        createdAt: string;
    }[];
}

export default function InstitutionStudentsTable({
    enrolments,
}: InstitutionStudentsTableProps) {
    return (
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
                        {enrolments.map(enrolment => (
                            <tr key={enrolment.id}>
                                <td className="whitespace-nowrap px-2 py-2 text-sm text-gray-900">
                                    <span className="font-semibold">
                                        {enrolment.name.lastName.toUpperCase()}
                                    </span>
                                    , {enrolment.name.firstName}
                                </td>
                                <td className="py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                    <StudentRegistrationId className="truncate text-left">
                                        {enrolment.studentRegistrationId}
                                    </StudentRegistrationId>
                                </td>
                                <td className="py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                    {enrolment.studentGroup}
                                </td>
                                <td className="relative py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a
                                        href={route(
                                            'admin.studentGroupEnrolments.read.overview',
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
    );
}
