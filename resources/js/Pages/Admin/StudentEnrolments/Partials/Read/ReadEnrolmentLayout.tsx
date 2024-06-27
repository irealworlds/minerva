import {
    AcademicCapIcon,
    ChartPieIcon,
    ClipboardDocumentListIcon,
    DocumentDuplicateIcon,
    IdentificationIcon,
    ListBulletIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { PropsWithChildren, useMemo } from 'react';
import { StudentEnrolmentDetailsViewModel } from '@/types/view-models/student-enrolment-details.view-model';
import { Link } from '@inertiajs/react';

interface EnrolmentManagementLayoutProps extends PropsWithChildren {
    enrolment: StudentEnrolmentDetailsViewModel;
}

export default function ReadEnrolmentLayout({
    children,
    enrolment,
}: EnrolmentManagementLayoutProps) {
    const navigation = useMemo(() => {
        return [
            {
                name: 'Overview',
                href: route('admin.studentGroupEnrolments.read.overview', {
                    enrolment: enrolment.id,
                }),
                icon: IdentificationIcon,
                current: route().current(
                    'admin.studentGroupEnrolments.read.overview'
                ),
                disabled: false,
            },
            {
                name: 'Disciplines',
                href: route('admin.studentGroupEnrolments.read.disciplines', {
                    enrolment: enrolment.id,
                }),
                icon: AcademicCapIcon,
                current: route().current(
                    'admin.studentGroupEnrolments.read.disciplines'
                ),
                disabled: false,
            },
            {
                name: 'Grades',
                href: '#',
                count: 'soon',
                icon: ClipboardDocumentListIcon,
                current: false,
                disabled: true,
            },
            {
                name: 'Documents',
                href: '#',
                icon: DocumentDuplicateIcon,
                count: 'soon',
                current: false,
                disabled: true,
            },
            {
                name: 'Reports',
                href: '#',
                icon: ChartPieIcon,
                count: 'soon',
                current: false,
                disabled: true,
            },
            {
                name: 'History',
                href: '#',
                icon: ListBulletIcon,
                count: 'soon',
                current: false,
                disabled: true,
            },
        ];
    }, [enrolment]);

    const secondaryNavigation = useMemo(
        () =>
            enrolment.allEnrolmentsList
                .filter(e => e.id !== enrolment.id)
                .map(e => ({
                    name: e.name,
                    href: route('admin.studentGroupEnrolments.read.overview', {
                        enrolment: e.id,
                    }),
                    initial: e.name.trim().charAt(0),
                })),
        [enrolment]
    );

    return (
        <>
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-x-6 gap-y-6">
                <div>
                    <nav className="flex flex-1 flex-col" aria-label="Sidebar">
                        <ul
                            role="list"
                            className="flex flex-1 flex-col gap-y-7">
                            {/* Primary navigation */}
                            <li>
                                <ul role="list" className="space-y-1">
                                    {navigation.map(item => (
                                        <li key={item.name}>
                                            <Link
                                                href={item.href}
                                                className={combineClassNames(
                                                    item.current
                                                        ? 'bg-gray-100 text-indigo-600'
                                                        : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600',
                                                    item.disabled
                                                        ? 'pointer-events-none select-none opacity-50'
                                                        : '',
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
                                                {item.count ? (
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

                            {/* Other enrolments */}
                            {secondaryNavigation.length > 0 && (
                                <li>
                                    <div className="text-xs font-semibold leading-6 text-gray-400">
                                        Other enrolments
                                    </div>
                                    <ul
                                        role="list"
                                        className="-mx-2 mt-2 space-y-1">
                                        {secondaryNavigation
                                            .slice(0, 3)
                                            .map(item => (
                                                <li key={item.name}>
                                                    <Link
                                                        href={item.href}
                                                        className="text-gray-700 hover:bg-gray-100 hover:text-indigo-600 group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                        <span className="border-gray-200 text-gray-400 group-hover:border-indigo-600 group-hover:text-indigo-600 flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium">
                                                            <UserGroupIcon className="size-4" />
                                                        </span>
                                                        <span className="truncate">
                                                            {item.name}
                                                        </span>
                                                    </Link>
                                                </li>
                                            ))}
                                        {secondaryNavigation.length > 3 && (
                                            <li className="text-xs leading-6 text-gray-400 ml-2 tracking-wide">
                                                +
                                                {secondaryNavigation.length - 3}{' '}
                                                more
                                            </li>
                                        )}
                                    </ul>
                                </li>
                            )}
                        </ul>
                    </nav>
                </div>
                <div className="col-span-3">{children}</div>
            </div>
        </>
    );
}
