import { EducatorViewModel } from '@/types/view-models/admin/educator.view-model';
import { ChevronRightIcon } from '@heroicons/react/20/solid';
import { Link } from '@inertiajs/react';

interface EducatorsListProps {
    educators: EducatorViewModel[];
}

export default function EducatorsList({ educators }: EducatorsListProps) {
    return (
        <ul
            role="list"
            className="divide-y divide-gray-100 overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
            {educators.map(educator => (
                <li
                    key={educator.key}
                    className="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 sm:px-6">
                    <div className="flex min-w-0 gap-x-4">
                        <img
                            className="h-12 w-12 flex-none rounded-full bg-gray-50"
                            src={educator.pictureUri}
                            alt=""
                        />
                        <div className="min-w-0 flex-auto">
                            <p className="text-sm font-semibold leading-6 text-gray-900">
                                <Link
                                    href={route(
                                        'admin.educators.read.overview',
                                        { educator: educator.key }
                                    )}>
                                    <span className="absolute inset-x-0 -top-px bottom-0" />
                                    {educator.directoryName}
                                </Link>
                            </p>
                            <p className="mt-1 flex text-xs leading-5 text-gray-500">
                                <a
                                    href={`mailto:${educator.email}`}
                                    className="relative truncate hover:underline">
                                    {educator.email}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div className="flex shrink-0 items-center gap-x-4">
                        <div className="hidden sm:flex sm:flex-col sm:items-end">
                            <p className="text-sm leading-6 text-gray-900">
                                Since{' '}
                                <time
                                    dateTime={new Date(
                                        educator.createdAt
                                    ).toISOString()}>
                                    {new Date(
                                        educator.createdAt
                                    ).toLocaleDateString(undefined, {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric',
                                    })}
                                </time>
                            </p>
                        </div>
                        <ChevronRightIcon
                            className="h-5 w-5 flex-none text-gray-400"
                            aria-hidden="true"
                        />
                    </div>
                </li>
            ))}
        </ul>
    );
}
