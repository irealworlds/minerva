import { UserPlusIcon } from '@heroicons/react/24/outline';
import { Link } from '@inertiajs/react';
import { useContext } from 'react';
import { InstitutionManagementContext } from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';

export default function NoStudentEnrolments() {
    const { institution } = useContext(InstitutionManagementContext);

    return (
        <Link
            href={route('admin.studentGroupEnrolments.create', {
                institutionKey: institution?.id,
            })}
            className="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <UserPlusIcon className="mx-auto h-12 w-12 text-gray-400" />
            <span className="mt-2 block text-sm font-semibold text-gray-900">
                Add new enrolment
            </span>
        </Link>
    );
}
