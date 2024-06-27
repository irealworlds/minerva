import React, { useContext } from 'react';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { UserPlusIcon } from '@heroicons/react/24/outline';
import { PersonalNameDto } from '@/types/dtos/personal-name.dto';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import Paginator from '@/Components/Paginator';
import { InstitutionManagementContext } from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';
import InstitutionStudentsTable from '@/Pages/Admin/Institutions/Partials/Manage/Students/InstitutionStudentsTable';
import NoStudentEnrolments from '@/Pages/Admin/Institutions/Partials/Manage/Students/NoStudentEnrolments';
import { Link } from '@inertiajs/react';

interface InstitutionStudentsTableProps {
    enrolments: PaginatedCollection<{
        id: string;
        name: PersonalNameDto;
        studentRegistrationId: string;
        studentGroup: string;
        createdAt: string;
    }>;
}

export default function InstitutionStudentsList({
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
                    <Link
                        href={route('admin.student_enrolments.create', {
                            institutionKey: institution?.id,
                        })}>
                        <PrimaryButton type="button">
                            <UserPlusIcon className="size-5 mr-2" />
                            New enrolment
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
            <div className="mt-8 flow-root">
                {enrolments.total === 0 ? (
                    <NoStudentEnrolments />
                ) : (
                    <InstitutionStudentsTable enrolments={enrolments.data} />
                )}
            </div>

            <Paginator className="mt-4" collection={enrolments} />
        </div>
    );
}
