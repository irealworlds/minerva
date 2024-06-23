import {
    Dialog,
    DialogPanel,
    DialogTitle,
    Transition,
    TransitionChild,
} from '@headlessui/react';
import { OutstandingEducatorInvitationViewModel } from '@/types/view-models/outstanding-educator-invitation.view-model';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import OutstandingInstitutionEntry from '@/Pages/Admin/Institutions/Partials/Manage/Educators/OutstandingInstitutionEntry';

interface OutstandingInvitationsDialogProps {
    open: boolean;
    onClose: () => void;
    invitations: OutstandingEducatorInvitationViewModel[];
}

export default function OutstandingInvitationsDialog({
    open,
    onClose,
    invitations,
}: OutstandingInvitationsDialogProps) {
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
                            <DialogPanel className="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl sm:p-6">
                                <div>
                                    <div className="mt-3 sm:mt-5">
                                        <DialogTitle
                                            as="h3"
                                            className="text-base font-semibold leading-6 text-gray-900">
                                            Outstanding invitations
                                        </DialogTitle>
                                        <div className="mt-2">
                                            <ul
                                                role="list"
                                                className="divide-y divide-gray-100 overflow-y-auto">
                                                {invitations.map(invitation => (
                                                    <OutstandingInstitutionEntry
                                                        key={invitation.id}
                                                        invitation={invitation}
                                                    />
                                                ))}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div className="mt-5 sm:mt-6 flex md:justify-end">
                                    <SecondaryButton
                                        type="button"
                                        className="inline-flex w-full md:w-auto justify-center"
                                        onClick={() => {
                                            onClose();
                                        }}>
                                        Close
                                    </SecondaryButton>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </Transition>
    );
}
