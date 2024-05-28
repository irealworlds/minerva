import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { BuildingLibraryIcon } from '@heroicons/react/24/outline';
import React from 'react';
import { Link } from '@inertiajs/react';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import Paginator from '@/Components/Paginator';

export default function InstitutionsTable({
    institutions,
}: {
    institutions: PaginatedCollection<InstitutionViewModel>;
}) {
    const directory = institutions.data.reduce(
        (accumulator: Record<string, InstitutionViewModel[]>, institution) => {
            const firstLetter =
                institution.name.trim().at(0)?.toUpperCase() ?? '#';
            if (!(firstLetter in accumulator)) {
                accumulator[firstLetter] = [];
            }

            accumulator[firstLetter].push(institution);

            return accumulator;
        },
        {}
    );

    return (
        <>
            <div className="grow bg-white shadow rounded-lg">
                <nav className="h-full overflow-y-auto" aria-label="Directory">
                    {Object.keys(directory).map(letter => (
                        <div key={letter} className="relative">
                            <div className="sticky top-0 z-10 border-y border-b-gray-200 border-t-gray-100 bg-gray-50 px-3 py-1.5 text-sm font-semibold leading-6 text-gray-900">
                                <h3>{letter}</h3>
                            </div>
                            <ul
                                role="list"
                                className="divide-y divide-gray-100">
                                {directory[letter].map(institution => (
                                    <li
                                        key={institution.id}
                                        className="flex gap-x-4 px-3 py-5">
                                        {institution.pictureUri ? (
                                            <img
                                                className="size-12 flex-none rounded-full bg-gray-50 border shadow"
                                                src={institution.pictureUri}
                                                alt=""
                                            />
                                        ) : (
                                            <div
                                                className="size-12 bg-gray-800 flex items-center justify-center rounded-full text-white"
                                                aria-hidden="true">
                                                <BuildingLibraryIcon className="size-8" />
                                            </div>
                                        )}
                                        <div className="min-w-0">
                                            <p className="text-sm font-semibold leading-6 text-gray-900">
                                                <Link
                                                    href={route(
                                                        'institutions.show',
                                                        institution.id
                                                    )}>
                                                    {institution.name}
                                                </Link>
                                            </p>
                                            <p className="mt-1 truncate text-xs leading-5 text-gray-500"></p>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </nav>

                <div className="mt-6 border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <Paginator collection={institutions} />
                </div>
            </div>
        </>
    );
}
