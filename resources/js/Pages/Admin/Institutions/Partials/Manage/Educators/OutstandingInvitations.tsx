import { OutstandingEducatorInvitationViewModel } from '@/types/view-models/outstanding-educator-invitation.view-model';
import { combineClassNames } from '@/utils/combine-class-names.function';
import OutstandingInvitationsDialog from '@/Pages/Admin/Institutions/Partials/Manage/Educators/OutstandingInvitationsDialog';
import { useState } from 'react';
import { ArrowRightIcon } from '@heroicons/react/20/solid';

interface OutstandingInvitationsProps {
    invitations: OutstandingEducatorInvitationViewModel[];
    className?: string;
}

export default function OutstandingInvitations({
    invitations,
    className,
}: OutstandingInvitationsProps) {
    const [dialogOpen, setDialogOpen] = useState(false);
    return (
        <>
            <OutstandingInvitationsDialog
                invitations={invitations}
                open={dialogOpen}
                onClose={() => {
                    setDialogOpen(false);
                }}
            />
            <div
                className={combineClassNames(
                    'rounded-md bg-blue-50 p-4',
                    className
                )}>
                <div className="flex i">
                    <div className="flex-shrink-0">
                        <div className="isolate flex -space-x-2">
                            {invitations
                                .slice(0, invitations.length > 4 ? 3 : 4)
                                .map(invitation => (
                                    <img
                                        key={invitation.id}
                                        className="relative z-30 inline-block size-6 rounded-full ring-2 ring-blue-50"
                                        src={invitation.pictureUri}
                                        alt=""
                                    />
                                ))}
                            {invitations.length > 4 && (
                                <div className="relative z-30 size-6 rounded-full ring-2 ring-blue-50 bg-gray-300 text-gray-900 inline-flex items-center justify-center text-xs">
                                    +{invitations.length - 3}
                                </div>
                            )}
                        </div>
                    </div>
                    <div className="ml-3 flex-1 md:flex md:justify-between">
                        <p className="text-sm text-blue-700">
                            There are {invitations.length} outstanding
                            invitations waiting for a reply.
                        </p>
                        <p className="mt-3 text-sm md:ml-6 md:mt-0">
                            <button
                                type="button"
                                onClick={() => {
                                    setDialogOpen(true);
                                }}
                                className="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600 inline-flex items-center gap-1">
                                Details
                                <span aria-hidden="true">
                                    <ArrowRightIcon className="size-4" />
                                </span>
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </>
    );
}
