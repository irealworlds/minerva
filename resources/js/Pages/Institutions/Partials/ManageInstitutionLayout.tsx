import { PropsWithChildren, useMemo } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { Link } from '@inertiajs/react';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import { minimizeNumber } from '@/utils/minimize-number.function';
import { InstitutionViewModel } from '@/types/ViewModels/institution.view-model';

const navigationItems = [
    { name: 'General', tabName: 'General' },
    { name: 'Group structure', tabName: 'Groups' },
    { name: 'Educators', tabName: 'Educators', count: 12 },
    { name: 'Student enrollments', tabName: 'Enrollments', count: 5000 },
];

export default function ManageInstitutionLayout({
    children,
    institution,
    activeTab,
}: PropsWithChildren<{
    institution: InstitutionViewModel;
    activeTab: string;
}>) {
    const navigation = useMemo(
        () =>
            navigationItems.map(item => ({
                ...item,
                href: route('institutions.show', {
                    institution: institution.id,
                    tab: item.tabName,
                }),
                current: item.tabName.toLowerCase() === activeTab.toLowerCase(),
            })),
        [activeTab, institution]
    );

    return (
        <div className="grid grid-cols-1 xl:grid-cols-3 2xl:grid-cols-6 gap-x-12 gap-y-4">
            <Link
                href={route('institutions.index')}
                className="inline-flex items-center gap-x-3 pl-1 text-sm leading-6 font-semibold text-gray-700 hover:text-indigo-600">
                <ArrowLeftIcon className="size-4" />
                Back to list
            </Link>
            <div className="xl:col-span-2 2xl:col-span-5">
                <h4 className="text-lg font-semibold">{institution.name}</h4>
            </div>

            <div className="relative w-full">
                {/* TODO Find a better way to sticky-position this */}
                <nav
                    className="flex flex-1 flex-col sticky top-20"
                    aria-label="Sidebar">
                    <ul role="list" className="-mx-2 space-y-1">
                        {navigation.map(item => (
                            <li key={item.name}>
                                <Link
                                    href={item.href}
                                    className={combineClassNames(
                                        item.current
                                            ? 'bg-gray-100 text-indigo-600'
                                            : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100',
                                        'group flex gap-x-3 rounded-md p-2 pl-3 text-sm leading-6 font-semibold'
                                    )}>
                                    {item.name}
                                    {item.count ? (
                                        <span
                                            className="ml-auto w-9 min-w-max whitespace-nowrap rounded-full bg-white px-2.5 py-0.5 text-center text-xs font-medium leading-5 text-gray-600 ring-1 ring-inset ring-gray-200"
                                            aria-hidden="true">
                                            {minimizeNumber(item.count)}
                                        </span>
                                    ) : null}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </nav>
            </div>
            <div className="xl:col-span-2 2xl:col-span-5">{children}</div>
        </div>
    );
}
