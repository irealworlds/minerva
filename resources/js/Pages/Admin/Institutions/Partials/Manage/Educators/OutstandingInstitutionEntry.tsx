import { OutstandingEducatorInvitationViewModel } from '@/types/view-models/outstanding-educator-invitation.view-model';
import { useForm } from '@inertiajs/react';
import { useContext } from 'react';
import { InstitutionManagementContext } from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';

interface OutstandingInstitutionEntryProps {
    invitation: OutstandingEducatorInvitationViewModel;
}

export default function OutstandingInstitutionEntry({
    invitation,
}: OutstandingInstitutionEntryProps) {
    const { processing: revoking, delete: sendRevokeRequest } = useForm();

    const { institution } = useContext(InstitutionManagementContext);

    function revoke() {
        if (revoking) {
            throw new Error('Revoke request is already in progress');
        }

        if (!institution) {
            throw new Error('Institution is not set');
        }

        sendRevokeRequest(
            route('admin.educator_invitations.delete', {
                invitation: invitation.id,
            })
        );
    }

    return (
        <li className="flex justify-between gap-x-6 py-5">
            <div className="flex min-w-0 gap-x-4">
                <img
                    className="h-12 w-12 flex-none rounded-full bg-gray-50"
                    src={invitation.pictureUri}
                    alt=""
                />
                <div className="min-w-0 flex-auto">
                    <p className="text-sm font-semibold leading-6 text-gray-900">
                        {invitation.name}
                    </p>
                    {invitation.roles.length > 0 && (
                        <p className="mt-1 truncate text-xs leading-5 text-gray-500">
                            Invited to be {invitation.roles.join(', ')}
                        </p>
                    )}
                </div>
            </div>
            <div className="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <button
                    type="button"
                    onClick={() => {
                        revoke();
                    }}
                    disabled={revoking}
                    className="text-sm leading-6 text-indigo-600 hover:text-indigo-500 disabled:opacity-50">
                    {revoking ? 'Revoking' : 'Revoke'}
                </button>

                <div className="mt-1 flex items-center gap-x-1.5">
                    <div className="flex-none rounded-full bg-gray-300 p-1">
                        <div className="size-1.5 rounded-full bg-gray-500" />
                    </div>
                    <p className="text-xs leading-5 text-gray-500">
                        Pending reply
                    </p>
                </div>
            </div>
        </li>
    );
}
