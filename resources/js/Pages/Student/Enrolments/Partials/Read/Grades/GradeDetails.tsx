import {
    Dialog,
    DialogBackdrop,
    DialogPanel,
    TransitionChild,
} from '@headlessui/react';
import { XMarkIcon } from '@heroicons/react/24/outline';
import { GradeDetailsViewModel } from '@/types/view-models/student/grade-details.view-model';

interface GradeDetailsProps {
    grade: GradeDetailsViewModel | null;
    open: boolean;
    onClose: () => void;
}

export default function GradeDetails({
    open,
    onClose,
    grade,
}: GradeDetailsProps) {
    return (
        <Dialog
            className="relative z-50"
            open={open}
            onClose={() => {
                onClose();
            }}>
            <DialogBackdrop
                transition
                className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity duration-500 ease-in-out data-[closed]:opacity-0"
            />

            <div className="fixed inset-0 overflow-hidden">
                <div className="absolute inset-0 overflow-hidden">
                    <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <DialogPanel
                            transition
                            className="pointer-events-auto relative w-96 transform transition duration-500 ease-in-out data-[closed]:translate-x-full sm:duration-700">
                            <TransitionChild>
                                <div className="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 duration-500 ease-in-out data-[closed]:opacity-0 sm:-ml-10 sm:pr-4">
                                    <button
                                        type="button"
                                        className="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                        onClick={() => {
                                            onClose();
                                        }}>
                                        <span className="absolute -inset-2.5" />
                                        <span className="sr-only">
                                            Close panel
                                        </span>
                                        <XMarkIcon
                                            className="h-6 w-6"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div className="h-full overflow-y-auto bg-white p-8">
                                <div className="space-y-6 pb-16">
                                    <div>
                                        <div className="flex items-start justify-between">
                                            <div>
                                                <h2 className="text-base font-semibold leading-6 text-gray-900">
                                                    Grade overview
                                                </h2>
                                                {grade && (
                                                    <p className="text-sm font-medium text-gray-500">
                                                        for your grade in{' '}
                                                        {grade.disciplineName}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                    {grade && (
                                        <>
                                            <div>
                                                <h3 className="font-medium text-gray-900">
                                                    Information
                                                </h3>
                                                <dl className="mt-2 divide-y divide-gray-200 border-b border-t border-gray-200">
                                                    <div className="flex justify-between py-3 text-sm font-medium">
                                                        <dt className="text-gray-500">
                                                            Awarded points
                                                        </dt>
                                                        <dd className="text-gray-900">
                                                            {grade.awardedPoints.toLocaleString()}
                                                        </dd>
                                                    </div>
                                                    <div className="flex justify-between py-3 text-sm font-medium">
                                                        <dt className="text-gray-500">
                                                            Maximum points
                                                        </dt>
                                                        <dd className="text-gray-900">
                                                            {grade.maximumPoints.toLocaleString()}
                                                        </dd>
                                                    </div>
                                                    <div className="flex justify-between py-3 text-sm font-medium">
                                                        <dt className="text-gray-500">
                                                            Awarded by
                                                        </dt>
                                                        <dd className="text-gray-900">
                                                            <div className="flex items-center gap-3">
                                                                <p className="text-sm font-medium text-gray-900 text-right">
                                                                    {
                                                                        grade.educatorName
                                                                    }
                                                                </p>
                                                                <img
                                                                    src={
                                                                        grade.educatorPictureUri
                                                                    }
                                                                    alt=""
                                                                    className="size-8 rounded-full"
                                                                />
                                                            </div>
                                                        </dd>
                                                    </div>
                                                    <div className="flex justify-between py-3 text-sm font-medium">
                                                        <dt className="text-gray-500">
                                                            Discipline
                                                        </dt>
                                                        <dd className="text-gray-900">
                                                            {
                                                                grade.disciplineName
                                                            }
                                                        </dd>
                                                    </div>
                                                    <div className="flex justify-between py-3 text-sm font-medium">
                                                        <dt className="text-gray-500">
                                                            Award datea
                                                        </dt>
                                                        <dd className="text-gray-900">
                                                            <time
                                                                dateTime={new Date(
                                                                    grade.awardedAt
                                                                ).toISOString()}>
                                                                {new Date(
                                                                    grade.awardedAt
                                                                ).toLocaleDateString(
                                                                    undefined,
                                                                    {
                                                                        month: 'long',
                                                                        day: 'numeric',
                                                                        year: 'numeric',
                                                                    }
                                                                )}
                                                            </time>
                                                        </dd>
                                                    </div>
                                                </dl>
                                            </div>

                                            <div>
                                                <h3 className="font-medium text-gray-900">
                                                    Notes
                                                </h3>
                                                <div className="mt-2 flex items-center justify-between">
                                                    {grade.notes.length ===
                                                    0 ? (
                                                        <p className="text-sm italic text-gray-500">
                                                            No notes added to
                                                            this grade
                                                        </p>
                                                    ) : (
                                                        <p className="text-sm text-gray-500">
                                                            {grade.notes}
                                                        </p>
                                                    )}
                                                </div>
                                            </div>
                                        </>
                                    )}
                                </div>
                            </div>
                        </DialogPanel>
                    </div>
                </div>
            </div>
        </Dialog>
    );
}
