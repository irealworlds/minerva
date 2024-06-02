import { useContext, useState } from 'react';
import { router } from '@inertiajs/react';
import { InstitutionManagementContext } from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import { EducatorManagementContext } from '@/Pages/Institutions/Partials/Manage/Educators/EducatorDetailsOverlay';

interface EducatorDetailsRoleEntryProps {
    role: string;
    index: number;
}

export default function EducatorDetailsRoleEntry({
    role,
    index,
}: EducatorDetailsRoleEntryProps) {
    const [revoking, setRevoking] = useState(false);
    const { institution } = useContext(InstitutionManagementContext);
    const { educator } = useContext(EducatorManagementContext);

    function revokeRole() {
        if (!institution) {
            throw new Error('Institution is not set');
        }
        if (!educator) {
            throw new Error('Educator is not set');
        }

        router.visit(
            route('institutions.educators.roles.delete', {
                institution: institution.id,
                educator: educator.id,
                role: role,
            }),
            {
                method: 'delete',
                preserveState: true,
                onStart: () => {
                    setRevoking(true);
                },
                onFinish: () => {
                    setRevoking(false);
                },
            }
        );
    }

    return (
        <li
            key={index}
            className="flex gap-6 items-center justify-between py-3">
            <div className="flex items-center gap-4">
                <div className="size-8 flex items-center justify-center text-xl text-gray-300 select-none">
                    {index}.
                </div>
                <p className="text-sm font-medium text-gray-900">{role}</p>
            </div>
            <button
                type="button"
                onClick={() => {
                    revokeRole();
                }}
                disabled={revoking}
                className="rounded-md bg-white text-sm font-medium text-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 disabled:opacity-50">
                {revoking ? 'Revoking' : 'Revoke'}
                <span className="sr-only">role {role}</span>
            </button>
        </li>
    );
}
