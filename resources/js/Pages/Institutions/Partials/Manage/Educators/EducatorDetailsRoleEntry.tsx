import { useContext, useState } from 'react';
import { router } from '@inertiajs/react';
import { InstitutionManagementContext } from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import { EducatorManagementContext } from '@/Pages/Institutions/Partials/Manage/Educators/EducatorDetailsOverlay';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface EducatorDetailsRoleEntryProps {
    role: string;
}

export default function EducatorDetailsRoleEntry({
    role,
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
        <div className="flex justify-between gap-x-6">
            <div className={combineClassNames(revoking ? 'opacity-50' : '')}>
                <span className="font-medium text-gray-900">{role}</span>
            </div>

            {/* Revoke button */}
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
        </div>
    );
}
