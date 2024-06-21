import { createContext, PropsWithChildren, useMemo } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { Link } from '@inertiajs/react';
import {
    AcademicCapIcon,
    AdjustmentsHorizontalIcon,
    ArrowLeftIcon,
    FolderIcon,
    IdentificationIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

export const InstitutionManagementContext = createContext<{
    institution?: InstitutionViewModel;
}>({});

export default function ManageInstitutionLayout({
    children,
    institution,
}: PropsWithChildren<{
    institution: InstitutionViewModel;
}>) {
    const navigation = useMemo(
        () =>
            [
                {
                    name: 'General',
                    icon: AdjustmentsHorizontalIcon,
                    route: 'admin.institutions.show.general',
                    count: undefined,
                },
                {
                    name: 'Group structure',
                    icon: UserGroupIcon,
                    route: 'admin.institutions.show.groups',
                    count: undefined,
                },
                {
                    name: 'Disciplines',
                    icon: FolderIcon,
                    route: 'admin.institutions.show.disciplines',
                    count: institution.disciplinesCount,
                },
                {
                    name: 'Educators',
                    icon: AcademicCapIcon,
                    route: 'admin.institutions.show.educators',
                    count: institution.educatorsCount,
                },
                {
                    name: 'Student enrolments',
                    icon: IdentificationIcon,
                    route: 'admin.institutions.show.students',
                    count: institution.studentsCount,
                },
            ].map(item => ({
                ...item,
                href: route(item.route, {
                    institution: institution.id,
                }),
                current: route().current(item.route),
            })),
        [institution]
    );

    const secondaryNavigation = useMemo(
        () =>
            institution.childInstitutions.map(child => ({
                name: child.name,
                href: route('admin.institutions.show.general', {
                    institution: child.id,
                }),
                initial: child.name.trim().charAt(0),
            })),
        [institution]
    );

    return (
        <div className="grid grid-cols-1 xl:grid-cols-3 2xl:grid-cols-6 gap-x-12 gap-y-4">
            <Link
                href={route('admin.institutions.index')}
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
                    <ul role="list" className="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" className="-mx-2 space-y-1">
                                {navigation.map(item => (
                                    <li key={item.name}>
                                        <Link
                                            href={item.href}
                                            className={combineClassNames(
                                                item.current
                                                    ? 'bg-gray-100 text-indigo-600'
                                                    : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600',
                                                'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6'
                                            )}>
                                            <item.icon
                                                className={combineClassNames(
                                                    item.current
                                                        ? 'text-indigo-600'
                                                        : 'text-gray-400 group-hover:text-indigo-600',
                                                    'h-6 w-6 shrink-0'
                                                )}
                                                aria-hidden="true"
                                            />
                                            {item.name}
                                            {item.count !== undefined ? (
                                                <span
                                                    className="ml-auto w-9 min-w-max whitespace-nowrap rounded-full bg-gray-50 px-2.5 py-0.5 text-center text-xs font-medium leading-5 text-gray-600 ring-1 ring-inset ring-gray-200"
                                                    aria-hidden="true">
                                                    {item.count}
                                                </span>
                                            ) : null}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </li>
                        {secondaryNavigation.length > 0 && (
                            <li>
                                <div className="text-xs font-semibold leading-6 text-gray-400">
                                    Associated institutions
                                </div>
                                <ul
                                    role="list"
                                    className="-mx-2 mt-2 space-y-1">
                                    {secondaryNavigation.map(item => (
                                        <li key={item.name}>
                                            <Link
                                                href={item.href}
                                                className={combineClassNames(
                                                    'text-gray-700 hover:bg-gray-100 hover:text-indigo-600',
                                                    'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6'
                                                )}>
                                                <span
                                                    className={combineClassNames(
                                                        'border-gray-200 text-gray-400 group-hover:border-indigo-600 group-hover:text-indigo-600',
                                                        'flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium'
                                                    )}>
                                                    {item.initial}
                                                </span>
                                                <span className="truncate">
                                                    {item.name}
                                                </span>
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </li>
                        )}
                    </ul>
                </nav>
            </div>
            <div className="xl:col-span-2 2xl:col-span-5">
                <InstitutionManagementContext.Provider value={{ institution }}>
                    {children}
                </InstitutionManagementContext.Provider>
            </div>
        </div>
    );
}
