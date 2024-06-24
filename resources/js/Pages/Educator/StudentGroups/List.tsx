import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import {
    BarsArrowUpIcon,
    ChevronDownIcon,
    MagnifyingGlassIcon,
} from '@heroicons/react/20/solid';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import StudentGroupCard from '@/Pages/Educator/StudentGroups/Partials/List/StudentGroupCard';
import { EducatorStudentGroupViewModel } from '@/types/view-models/educator/educator-student-group.view-model';
import Paginator from '@/Components/Paginator';
import { useEffect, useRef, useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';
import { UserGroupIcon } from '@heroicons/react/24/outline';

type ListPageProps = PageProps<{
    studentGroups: PaginatedCollection<EducatorStudentGroupViewModel>;
    initialFilters: {
        searchQuery: string;
    };
}>;

export default function List({
    auth,
    studentGroups,
    initialFilters,
}: ListPageProps) {
    const [searchQuery, setSearchQuery] = useState(initialFilters.searchQuery);
    const hasMounted = useRef(false);

    const updateResults = useDebouncedCallback(() => {
        if (!hasMounted.current) {
            hasMounted.current = true;
            return;
        }

        const queryParams: Record<string, string> = {};

        if (searchQuery.trim().length > 0) {
            queryParams.searchQuery = searchQuery.trim().toString();
        }

        router.visit(route('educators.studentGroups.list', queryParams), {
            only: ['studentGroups', 'initialFilters'],
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);

    useEffect(() => {
        updateResults();
    }, [searchQuery]);

    useEffect(() => {
        setSearchQuery(initialFilters.searchQuery);
    }, [initialFilters]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="List" />

            {/* Heading */}
            <div className="border-b border-gray-200 pb-5 sm:flex sm:items-center sm:justify-between">
                <div className="-ml-2 -mt-2 flex flex-wrap items-baseline">
                    <h3 className="ml-2 mt-2 text-base font-semibold leading-6 text-gray-900">
                        Student groups
                    </h3>
                    <p className="ml-2 mt-1 truncate text-sm text-gray-500">
                        where you teach
                    </p>
                </div>
                <div className="mt-3 sm:ml-4 sm:mt-0">
                    <label htmlFor="mobile-search-groups" className="sr-only">
                        Search
                    </label>
                    <label htmlFor="desktop-search-groups" className="sr-only">
                        Search
                    </label>
                    <div className="flex rounded-md shadow-sm">
                        <div className="relative flex-grow focus-within:z-10">
                            <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <MagnifyingGlassIcon
                                    className="h-5 w-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </div>
                            <input
                                value={searchQuery}
                                onChange={event => {
                                    setSearchQuery(event.target.value);
                                }}
                                type="search"
                                name="mobile-search-groups"
                                id="mobile-search-groups"
                                className="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:hidden"
                                placeholder="Search"
                            />
                            <input
                                value={searchQuery}
                                onChange={event => {
                                    setSearchQuery(event.target.value);
                                }}
                                type="search"
                                name="desktop-search-groups"
                                id="desktop-search-groups"
                                className="hidden w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:block"
                                placeholder="Search student groups"
                            />
                        </div>
                        <button
                            type="button"
                            className="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <BarsArrowUpIcon
                                className="-ml-0.5 h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                            Sort
                            <ChevronDownIcon
                                className="-mr-1 h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                        </button>
                    </div>
                </div>
            </div>

            {/*  List */}
            <div className="mt-6">
                {studentGroups.total === 0 ? (
                    <div className="text-center">
                        <UserGroupIcon className="mx-auto size-12 text-gray-400" />
                        <h3 className="mt-2 text-sm font-semibold text-gray-900">
                            No student groups
                        </h3>
                        <p className="mt-1 text-sm text-gray-500">
                            You are not currently teaching any student groups.
                        </p>
                    </div>
                ) : (
                    <>
                        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 gap-6">
                            {studentGroups.data.map(group => (
                                <StudentGroupCard
                                    key={group.id}
                                    studentGroup={group}
                                />
                            ))}
                        </div>
                        {studentGroups.last_page !== 1 && (
                            <Paginator
                                className="mt-6"
                                collection={studentGroups}
                            />
                        )}
                    </>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
