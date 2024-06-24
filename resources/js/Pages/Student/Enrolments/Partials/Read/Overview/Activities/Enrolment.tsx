import { UserPlusIcon } from '@heroicons/react/24/outline';

interface EnrolmentProps {
    enroledAt: string;
}

export default function Enrolment({ enroledAt }: EnrolmentProps) {
    return (
        <>
            <div>
                <div className="relative px-1">
                    <div className="flex size-8 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                        <UserPlusIcon
                            className="size-5 text-gray-500"
                            aria-hidden="true"
                        />
                    </div>
                </div>
            </div>
            <div className="min-w-0 flex-1 py-1.5">
                <div className="text-sm text-gray-500">
                    Has become enroled in this student group on{' '}
                    <span className="whitespace-nowrap">
                        <time dateTime={new Date(enroledAt).toISOString()}>
                            {new Date(enroledAt).toLocaleDateString(undefined, {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                            })}
                        </time>
                    </span>
                </div>
            </div>
        </>
    );
}
