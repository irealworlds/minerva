import { EducatorSuggestionViewModel } from '@/types/view-models/educator-suggestion.view-model';
import { CheckIcon, PlusIcon } from '@heroicons/react/20/solid';
import React, { useContext, useState } from 'react';
import Spinner from '@/Components/Spinner';
import axios from 'axios';
import { InstitutionManagementContext } from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';
import { toast } from 'react-toastify';
import { AppToastOptions } from '@/Root';
import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';
import { PaperAirplaneIcon, XMarkIcon } from '@heroicons/react/24/outline';

interface EducatorSuggestionEntryProps {
    suggestion: EducatorSuggestionViewModel;
}

export default function EducatorSuggestionEntry({
    suggestion,
}: EducatorSuggestionEntryProps) {
    const [preparingInvitation, setPreparingInvitation] = useState(false);
    const [processingInvitation, setProcessingInvitation] = useState(false);
    const [invitationSuccessful, setInvitationSuccessful] = useState(false);
    const [invitationRoles, setInvitationRoles] = useState<string[]>([]);

    const { institution } = useContext(InstitutionManagementContext);

    function sendInvitation() {
        if (!institution) {
            throw new Error('Institution not found.');
        }

        setProcessingInvitation(true);
        axios
            .post(route('api.educator_invitations.create'), {
                institutionKey: institution.id,
                email: suggestion.email,
                roles: invitationRoles,
            })
            .then(() => {
                setInvitationSuccessful(true);
                toast.success('Invitation sent successfully', AppToastOptions);
            })
            .catch(() => {
                setPreparingInvitation(false);
                setInvitationSuccessful(false);
                toast.error('Could not send invitation.', AppToastOptions);
            })
            .finally(() => {
                setProcessingInvitation(false);
            });
    }

    return (
        <li className="flex items-center justify-between space-x-3 py-4">
            <div className="flex min-w-0 flex-1 items-center space-x-3">
                <div className="flex-shrink-0">
                    <img
                        className="h-10 w-10 rounded-full"
                        src={suggestion.pictureUri}
                        alt=""
                    />
                </div>
                <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-medium text-gray-900">
                        {suggestion.name}
                    </p>
                    <p className="truncate text-sm font-medium text-gray-500">
                        Member of{' '}
                        <span className="font-semibold">
                            {suggestion.institutionsCount === 1
                                ? `1 institution`
                                : `${suggestion.institutionsCount.toString()} institutions`}
                        </span>
                    </p>
                </div>
            </div>
            <div className="flex-shrink-0">
                {invitationSuccessful ? (
                    <div className="inline-flex items-center gap-x-1.5 text-sm leading-6 text-emerald-500">
                        <CheckIcon className="size-4" aria-hidden="true" />{' '}
                        Invitation sent
                    </div>
                ) : preparingInvitation ? (
                    <>
                        <TextChipsInput
                            value={invitationRoles}
                            placeholder="Roles, split by comma"
                            onChange={roles => {
                                setInvitationRoles(roles);
                            }}
                        />
                        <div className="flex gap-4 mt-1">
                            <button
                                disabled={processingInvitation}
                                type="button"
                                onClick={() => {
                                    setPreparingInvitation(false);
                                }}
                                className="inline-flex items-center gap-x-1.5 text-sm font-semibold leading-6 text-gray-900 disabled:opacity-50">
                                <XMarkIcon
                                    className="size-5 text-gray-400"
                                    aria-hidden="true"
                                />{' '}
                                Cancel
                            </button>
                            <button
                                disabled={processingInvitation}
                                type="button"
                                onClick={() => {
                                    sendInvitation();
                                }}
                                className="inline-flex items-center gap-x-1.5 text-sm font-semibold leading-6 text-gray-900 disabled:opacity-50">
                                {processingInvitation ? (
                                    <>
                                        <Spinner
                                            className="size-3 text-gray-400"
                                            aria-hidden="true"
                                        />{' '}
                                        Inviting
                                    </>
                                ) : (
                                    <>
                                        <PaperAirplaneIcon
                                            className="size-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                        Send invitation
                                    </>
                                )}
                                <span className="sr-only">
                                    {' '}
                                    {suggestion.name}
                                </span>
                            </button>
                        </div>
                    </>
                ) : (
                    <button
                        disabled={processingInvitation}
                        type="button"
                        onClick={() => {
                            setPreparingInvitation(true);
                        }}
                        className="inline-flex items-center gap-x-1.5 text-sm font-semibold leading-6 text-gray-900 disabled:opacity-50">
                        <PlusIcon
                            className="size-5 text-gray-400"
                            aria-hidden="true"
                        />
                        Invite
                        <span className="sr-only"> {suggestion.name}</span>
                    </button>
                )}
            </div>
        </li>
    );
}
