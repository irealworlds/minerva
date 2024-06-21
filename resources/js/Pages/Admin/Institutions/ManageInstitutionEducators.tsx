import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import ManageInstitutionLayout from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import InstitutionEducatorsTable from '@/Pages/Admin/Institutions/Partials/Manage/Educators/InstitutionEducatorsTable';
import { InstitutionEducatorViewModel } from '@/types/view-models/institution-educator.view-model';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import React, { useEffect, useMemo, useState } from 'react';
import NoEducators from '@/Pages/Admin/Institutions/Partials/Manage/Educators/NoEducators';
import { EducatorSuggestionViewModel } from '@/types/view-models/educator-suggestion.view-model';
import { OutstandingEducatorInvitationViewModel } from '@/types/view-models/outstanding-educator-invitation.view-model';
import OutstandingInvitations from '@/Pages/Admin/Institutions/Partials/Manage/Educators/OutstandingInvitations';
import InviteEducatorDialog from '@/Pages/Admin/Institutions/Partials/Manage/Educators/InviteEducatorDialog';
import Paginator from '@/Components/Paginator';
import EducatorDetailsOverlay from '@/Pages/Admin/Institutions/Partials/Manage/Educators/EducatorDetailsOverlay';

export interface ManageInstitutionEducatorProps extends PageProps {
    institution: InstitutionViewModel;
    educators: PaginatedCollection<InstitutionEducatorViewModel>;
    suggestions: EducatorSuggestionViewModel[];
    outstandingInvitations: OutstandingEducatorInvitationViewModel[];
}

interface InvitationCreationRequest {
    institutionKey: string;
    email: string;
    roles: string[];
}

export default function ManageInstitutionEducators({
    auth,
    institution,
    educators,
    suggestions,
    outstandingInvitations,
}: ManageInstitutionEducatorProps) {
    const {
        data: creationData,
        post: sendCreationRequest,
        setData: setCreationData,
        processing: sendingInvitation,
        errors: creationErrors,
        reset: resetCreationForm,
        wasSuccessful: invitationSent,
    } = useForm<InvitationCreationRequest>({
        institutionKey: institution.id,
        email: '',
        roles: [],
    });

    const [inviteDialogOpen, setInviteDialogOpen] = useState(false);
    const [educatorDetailsOpen, setEducatorDetailsOpen] = useState(false);
    const [selectedEducatorId, setSelectedEducatorId] = useState<string | null>(
        null
    );

    useEffect(() => {
        if (invitationSent) {
            setInviteDialogOpen(false);
            resetCreationForm();
        }
    }, [invitationSent]);

    const selectedEducator = useMemo(() => {
        if (selectedEducatorId) {
            const educator = educators.data.find(
                e => e.id === selectedEducatorId
            );
            if (educator) {
                return educator;
            }
        }
        return null;
    }, [selectedEducatorId, educators]);

    function sendInvitation() {
        sendCreationRequest(route('admin.educator_invitations.create'));
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="ManageInstitutionEducators" />
            <ManageInstitutionLayout institution={institution}>
                <div className="bg-white p-6 rounded-lg shadow">
                    <InviteEducatorDialog
                        open={inviteDialogOpen}
                        onClose={() => {
                            setInviteDialogOpen(false);
                        }}
                        invitationEmail={creationData.email}
                        invitationRoles={creationData.roles}
                        onInvitationEmailChange={email => {
                            setCreationData('email', email);
                        }}
                        onInvitationRolesChange={roles => {
                            setCreationData('roles', roles);
                        }}
                        onSubmit={() => {
                            sendInvitation();
                        }}
                        errors={creationErrors}
                        submitting={sendingInvitation}
                    />

                    <EducatorDetailsOverlay
                        educator={selectedEducator}
                        open={educatorDetailsOpen && !!selectedEducator}
                        onClose={() => {
                            setEducatorDetailsOpen(false);
                        }}
                    />

                    {outstandingInvitations.length > 0 && (
                        <OutstandingInvitations
                            className="mb-6"
                            invitations={outstandingInvitations}
                        />
                    )}

                    {educators.total > 0 ? (
                        <>
                            <div className="flex items-center justify-between">
                                <div className="grow">
                                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                                        Educators
                                    </h2>
                                    <p className="mt-1 text-sm leading-6 text-gray-600">
                                        A list of educators registered to teach
                                        at this institution.
                                    </p>
                                </div>
                                <div>
                                    <PrimaryButton
                                        type="button"
                                        onClick={() => {
                                            setInviteDialogOpen(true);
                                        }}>
                                        Register new
                                    </PrimaryButton>
                                </div>
                            </div>
                            <InstitutionEducatorsTable
                                educators={educators.data}
                                onEducatorSelected={id => {
                                    setSelectedEducatorId(id);
                                    setEducatorDetailsOpen(true);
                                }}
                            />
                            <Paginator
                                className="mt-6"
                                collection={educators}
                            />
                        </>
                    ) : (
                        <NoEducators
                            institution={institution}
                            suggestions={suggestions}
                            openInvitationCreation={() => {
                                setInviteDialogOpen(true);
                            }}
                        />
                    )}
                </div>
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
