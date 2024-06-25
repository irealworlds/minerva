import { ChevronRightIcon } from '@heroicons/react/20/solid';
import { GradeViewModel } from '@/types/view-models/student/grade.view-model';

interface GradeEntryProps {
    grade: GradeViewModel;
    onSelected: () => void;
}

export default function GradeEntry({ grade, onSelected }: GradeEntryProps) {
    return (
        <button
            type="button"
            onClick={() => {
                onSelected();
            }}
            className="relative w-full flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 sm:px-6">
            <div className="flex min-w-0 gap-x-4">
                <div className="size-12 flex-none rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    {grade.awardedPoints.toLocaleString()}
                </div>
                <div className="min-w-0 flex-auto text-left">
                    <p className="text-sm font-semibold leading-6 text-gray-900">
                        <span className="absolute inset-x-0 -top-px bottom-0" />
                        out of {grade.maximumPoints.toLocaleString()}
                    </p>
                    <p className="mt-1 flex text-xs leading-5 text-gray-500">
                        in {grade.disciplineName}
                    </p>
                </div>
            </div>
            <div className="flex shrink-0 items-center gap-x-4">
                <div className="hidden sm:flex sm:flex-col sm:items-end">
                    <p className="text-sm leading-6 text-gray-900">
                        Awarded by {grade.educatorName}
                    </p>
                    <div className="mt-1 flex items-center gap-x-1.5">
                        <p className="text-xs leading-5 text-gray-500">
                            on{' '}
                            <time
                                dateTime={new Date(
                                    grade.awardedAt
                                ).toISOString()}>
                                {new Date(grade.awardedAt).toLocaleDateString(
                                    undefined,
                                    {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric',
                                    }
                                )}
                            </time>
                        </p>
                    </div>
                </div>
                <ChevronRightIcon
                    className="h-5 w-5 flex-none text-gray-400"
                    aria-hidden="true"
                />
            </div>
        </button>
    );
}
