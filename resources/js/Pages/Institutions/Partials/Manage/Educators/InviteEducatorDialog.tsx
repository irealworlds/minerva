import {
    Description,
    Dialog,
    DialogPanel,
    DialogTitle,
    Transition,
    TransitionChild,
} from '@headlessui/react';
import {
    EnvelopeIcon,
    PaperAirplaneIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import TextInput from '@/Components/Forms/Controls/TextInput';
import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import InputError from '@/Components/Forms/InputError';
import React, { FormEventHandler, useContext } from 'react';
import InputLabel from '@/Components/Forms/InputLabel';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import { InstitutionManagementContext } from '@/Pages/Institutions/Partials/ManageInstitutionLayout';

interface InviteEducatorDialogProps {
    open: boolean;
    onClose: () => void;
    invitationEmail: string;
    invitationRoles: string[];
    onInvitationEmailChange: (email: string) => void;
    onInvitationRolesChange: (roles: string[]) => void;
    onSubmit: () => void;
    submitting: boolean;
    errors: {
        email?: string;
        roles?: string;
    };
}

export default function InviteEducatorDialog({
    open,
    onClose,
    invitationEmail,
    invitationRoles,
    onInvitationEmailChange,
    onInvitationRolesChange,
    onSubmit,
    submitting,
    errors,
}: InviteEducatorDialogProps) {
    const submit: FormEventHandler = e => {
        e.preventDefault();
        onSubmit();
    };

    const { institution } = useContext(InstitutionManagementContext);

    return (
        <Transition show={open}>
            <Dialog
                className="relative z-50"
                onClose={() => {
                    onClose();
                }}>
                <TransitionChild
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0">
                    <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <div className="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <TransitionChild
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enterTo="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-200"
                            leaveFrom="opacity-100 translate-y-0 sm:scale-100"
                            leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel className="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                {/* Close button */}
                                <div className="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                                    <button
                                        type="button"
                                        className="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        onClick={() => {
                                            onClose();
                                        }}>
                                        <span className="sr-only">Close</span>
                                        <XMarkIcon
                                            className="size-6"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>

                                <form onSubmit={submit}>
                                    {/* Contents */}
                                    <div className="sm:flex sm:items-start">
                                        {/* Icon */}
                                        <div className="mx-auto flex size-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <EnvelopeIcon
                                                className="size-6 text-blue-600"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div className="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left grow">
                                            {/* Title */}
                                            <DialogTitle
                                                as="h3"
                                                className="text-base font-semibold leading-6 text-gray-900">
                                                Send invitation
                                            </DialogTitle>
                                            <Description className="text-sm text-gray-500 mt-2">
                                                Send an invitation for someone
                                                to join{' '}
                                                <span className="font-semibold">
                                                    {institution
                                                        ? institution.name
                                                        : 'your institution'}
                                                </span>{' '}
                                                as an educator with a set of
                                                roles.
                                            </Description>
                                            {/* Contents */}
                                            <div className="mt-6 space-y-4">
                                                {/* E-mail address */}
                                                <div>
                                                    <InputLabel
                                                        htmlFor="email"
                                                        value="Email address"
                                                    />
                                                    <TextInput
                                                        id="email"
                                                        type="email"
                                                        name="email"
                                                        value={invitationEmail}
                                                        disabled={submitting}
                                                        className="w-full block"
                                                        placeholder="Enter an e-mail address"
                                                        onChange={e => {
                                                            onInvitationEmailChange(
                                                                e.target.value
                                                            );
                                                        }}
                                                    />
                                                    <InputError
                                                        message={errors.email}
                                                        className="mt-2"
                                                    />
                                                </div>

                                                {/* Roles */}
                                                <div>
                                                    <InputLabel
                                                        htmlFor="roles"
                                                        value="Roles"
                                                    />
                                                    <TextChipsInput
                                                        id="roles"
                                                        name="roles"
                                                        value={invitationRoles}
                                                        disabled={submitting}
                                                        className="grow"
                                                        placeholder="Roles (e.g. Teacher, Principal, etc.)"
                                                        onChange={updated => {
                                                            onInvitationRolesChange(
                                                                updated
                                                            );
                                                        }}
                                                    />
                                                    <InputError
                                                        message={errors.roles}
                                                        className="mt-2"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Actions */}
                                    <div className="mt-4 sm:mt-6 flex flex-col sm:flex-row sm:justify-end gap-2">
                                        <SecondaryButton
                                            type="button"
                                            className="shrink-0 justify-center"
                                            disabled={submitting}
                                            onClick={() => {
                                                onClose();
                                            }}>
                                            Cancel
                                        </SecondaryButton>
                                        <PrimaryButton
                                            type="submit"
                                            className="shrink-0 justify-center"
                                            disabled={submitting}>
                                            {submitting ? 'Inviting' : 'Invite'}
                                            <PaperAirplaneIcon className="size-4 ml-2" />
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </Transition>
    );
}
