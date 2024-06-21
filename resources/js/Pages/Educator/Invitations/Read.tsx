import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { CheckIcon } from '@heroicons/react/20/solid';
import { ClockIcon, XMarkIcon } from '@heroicons/react/24/outline';
import { useMemo, useState } from 'react';

export interface ReadInvitationProps extends Record<string, unknown> {
    invitation: {
        institutionName: string;
        roles: string[];
        inviterPictureUri: string;
        inviterName: string;
        inviterEmail: string;
        expiredAt: string;
        respondedAt: string | null;
        accepted: boolean;
    };
}

export default function Read({
    auth,
    invitation,
}: PageProps<ReadInvitationProps>) {
    const [accepting, setAccepting] = useState(false);
    const [rejecting, setRejecting] = useState(false);

    const expirationDate = useMemo(
        () => new Date(invitation.expiredAt),
        [invitation]
    );

    const hasExpired = useMemo(
        () => expirationDate < new Date(),
        [expirationDate]
    );

    const processing = useMemo(
        () => accepting || rejecting,
        [accepting, rejecting]
    );

    function acceptInvitation() {
        router.reload({
            method: 'patch',
            data: {
                accepted: true,
            },
            onStart: () => {
                setAccepting(true);
            },
            onFinish: () => {
                setAccepting(false);
            },
        });
    }

    function declineInvitation() {
        router.reload({
            method: 'patch',
            data: {
                accepted: false,
            },
            onStart: () => {
                setRejecting(true);
            },
            onFinish: () => {
                setRejecting(false);
            },
        });
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Reply to educator invitation" />

            <div>
                {/* Header */}
                <div className="px-4 sm:px-0">
                    <h3 className="text-base font-semibold leading-7 text-gray-900">
                        Invitation
                    </h3>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                        Details about the invitation you received to join{' '}
                        <span className="font-semibold">
                            {invitation.institutionName}
                        </span>
                        .
                    </p>
                </div>

                {/* Details */}
                <div className="mt-6">
                    <dl className="grid grid-cols-1 sm:grid-cols-2">
                        {/* Institution */}
                        <div className="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt className="text-sm font-medium leading-6 text-gray-900">
                                Institution name
                            </dt>
                            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">
                                {invitation.institutionName}
                            </dd>
                        </div>

                        {/* Roles */}
                        <div className="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt className="text-sm font-medium leading-6 text-gray-900">
                                Roles
                            </dt>
                            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">
                                <ul className="list-disc pl-5">
                                    {invitation.roles.map((role, roleIdx) => (
                                        <li key={roleIdx}>{role}</li>
                                    ))}
                                </ul>
                            </dd>
                        </div>

                        {/* Inviter */}
                        <div className="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt className="text-sm font-medium leading-6 text-gray-900">
                                Inviter
                            </dt>
                            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">
                                <div className="flex items-center">
                                    <div>
                                        <img
                                            className="inline-block size-9 rounded-full"
                                            src={invitation.inviterPictureUri}
                                            alt=""
                                        />
                                    </div>
                                    <div className="ml-3">
                                        <p className="text-sm font-medium text-gray-700">
                                            {invitation.inviterName}
                                        </p>
                                        <p className="text-xs font-medium text-gray-500">
                                            <a
                                                href={`mailto:${invitation.inviterEmail}`}>
                                                {invitation.inviterEmail}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </dd>
                        </div>

                        {/* Status */}
                        <div className="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt className="text-sm font-medium leading-6 text-gray-900">
                                Status
                            </dt>
                            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">
                                {invitation.respondedAt === null ? (
                                    hasExpired ? (
                                        <div className="mt-1 flex items-center gap-x-1.5">
                                            <ClockIcon className="size-4 text-red-500" />
                                            <p className="text-xs leading-5 text-red-500">
                                                Expired on{' '}
                                                {expirationDate.toLocaleDateString(
                                                    undefined,
                                                    {
                                                        month: 'long',
                                                        day: 'numeric',
                                                        year: 'numeric',
                                                    }
                                                )}
                                            </p>
                                        </div>
                                    ) : (
                                        <div className="mt-1 flex items-center gap-x-1.5">
                                            <div className="flex-none rounded-full bg-gray-300 p-1">
                                                <div className="size-1.5 rounded-full bg-gray-500" />
                                            </div>
                                            <p className="text-xs leading-5 text-gray-500">
                                                Waiting for your response
                                            </p>
                                        </div>
                                    )
                                ) : invitation.accepted ? (
                                    <div className="mt-1 flex items-center gap-x-1.5">
                                        <CheckIcon className="size-4 text-emerald-500" />

                                        <p className="text-xs leading-5 text-emerald-500">
                                            Accepted at{' '}
                                            {new Date(
                                                invitation.respondedAt
                                            ).toLocaleDateString(undefined, {
                                                month: 'long',
                                                day: 'numeric',
                                                year: 'numeric',
                                            })}
                                        </p>
                                    </div>
                                ) : (
                                    <div className="mt-1 flex items-center gap-x-1.5">
                                        <XMarkIcon className="size-4 text-red-500" />
                                        <p className="text-xs leading-5 text-red-500">
                                            Declined at{' '}
                                            {new Date(
                                                invitation.respondedAt
                                            ).toLocaleDateString(undefined, {
                                                month: 'long',
                                                day: 'numeric',
                                                year: 'numeric',
                                            })}
                                        </p>
                                    </div>
                                )}
                            </dd>
                        </div>
                    </dl>
                </div>

                {/* Actions */}
                {invitation.respondedAt === null && !hasExpired && (
                    <div className="mt-6 w-full max-w-sm mx-auto flex flex-col space-y-4">
                        {/* Decline */}
                        <SecondaryButton
                            onClick={() => {
                                declineInvitation();
                            }}
                            className="justify-center gap-2"
                            disabled={processing}>
                            <XMarkIcon className="size-4" />
                            {rejecting
                                ? 'Declining invitation'
                                : 'Decline invitation'}
                        </SecondaryButton>

                        {/* Accept */}
                        <PrimaryButton
                            onClick={() => {
                                acceptInvitation();
                            }}
                            className="justify-center gap-2"
                            disabled={processing}>
                            <CheckIcon className="size-4" />
                            {accepting
                                ? 'Accepting invitation'
                                : 'Accept invitation'}
                        </PrimaryButton>

                        {/* Expiration */}
                        <div className="flex items-center justify-center gap-x-1.5">
                            <ClockIcon className="size-4 text-gray-500" />
                            <p className="text-xs leading-5 text-gray-500">
                                Expires on{' '}
                                {expirationDate.toLocaleDateString(undefined, {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric',
                                })}
                            </p>
                        </div>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
