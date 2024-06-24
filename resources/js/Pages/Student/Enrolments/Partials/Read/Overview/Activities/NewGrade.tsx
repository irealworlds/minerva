import { AcademicCapIcon } from '@heroicons/react/24/outline';

interface NewGradeProps {
    awardedBy: {
        name: string;
    };
    awardedPoints: number;
    maximumPoints: number;
    awardedAt: string;
    notes?: string;
}

export default function NewGrade({
    awardedBy,
    awardedPoints,
    maximumPoints,
    awardedAt,
    notes,
}: NewGradeProps) {
    return (
        <>
            <div>
                <div className="relative px-1">
                    <div className="flex size-8 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                        <AcademicCapIcon
                            className="size-5 text-gray-500"
                            aria-hidden="true"
                        />
                    </div>
                </div>
            </div>
            <div className="min-w-0 flex-1 py-1.5">
                <div className="text-sm text-gray-500">
                    <span className="font-medium text-gray-900">
                        {awardedBy.name}
                    </span>{' '}
                    awarded a new grade of{' '}
                    <span className="font-medium text-gray-900">
                        {awardedPoints}/{maximumPoints}
                    </span>{' '}
                    points on{' '}
                    <span className="whitespace-nowrap">
                        <time dateTime={new Date(awardedAt).toISOString()}>
                            {new Date(awardedAt).toLocaleDateString(undefined, {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                            })}
                        </time>
                    </span>
                </div>
                <div className="mt-2">
                    {!!notes?.length && (
                        <p className="text-sm text-gray-500">
                            <span className="font-medium">Notes:</span>{' '}
                            <span className="whitespace-pre-wrap">{notes}</span>
                        </p>
                    )}
                </div>
            </div>
        </>
    );
}
