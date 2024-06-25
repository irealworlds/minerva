import { createContext, PropsWithChildren } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import { Link } from '@inertiajs/react';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';

interface ReadLayoutProps extends PropsWithChildren {
    enrolment: StudentGroupEnrolmentViewModel;
}

export const StudentGroupEnrolmentManagementContext = createContext<{
    enrolment?: StudentGroupEnrolmentViewModel;
}>({});

export default function ReadLayout({ children, enrolment }: ReadLayoutProps) {
    const tabs = [
        {
            name: 'Overview',
            href: route('student.enrolments.read.overview', {
                enrolment: enrolment.key,
            }),
            current: route().current('student.enrolments.read.overview'),
        },
        {
            name: 'Disciplines',
            href: route('student.enrolments.read.disciplines', {
                enrolment: enrolment.key,
            }),
            current: route().current('student.enrolments.read.disciplines'),
        },
        {
            name: 'Grades',
            href: route('student.enrolments.read.grades', {
                enrolment: enrolment.key,
            }),
            current: route().current('student.enrolments.read.grades'),
        },
    ];
    return (
        <StudentGroupEnrolmentManagementContext.Provider value={{ enrolment }}>
            <div className="border-b border-gray-200 pb-5 sm:pb-0">
                <div className="flex items-center gap-x-3">
                    <Link href={route('student.enrolments.list')}>
                        <ArrowLeftIcon className="size-4" />
                    </Link>
                    <h1 className="flex gap-x-3 leading-7">
                        <span>{enrolment.institutionName}</span>
                        <span className="text-gray-600">/</span>
                        <span className="font-semibold">
                            {enrolment.studentGroupName}
                        </span>
                    </h1>
                </div>
                <div className="mt-3 sm:mt-4">
                    <div className="sm:hidden">
                        <label htmlFor="current-tab" className="sr-only">
                            Select a tab
                        </label>
                        <select
                            id="current-tab"
                            name="current-tab"
                            className="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                            defaultValue={tabs.find(tab => tab.current)?.name}>
                            {tabs.map(tab => (
                                <option key={tab.name}>{tab.name}</option>
                            ))}
                        </select>
                    </div>
                    <div className="hidden sm:block">
                        <nav className="-mb-px flex space-x-8">
                            {tabs.map(tab => (
                                <Link
                                    key={tab.name}
                                    href={tab.href}
                                    className={combineClassNames(
                                        tab.current
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                                        'whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium'
                                    )}
                                    aria-current={
                                        tab.current ? 'page' : undefined
                                    }>
                                    {tab.name}
                                </Link>
                            ))}
                        </nav>
                    </div>
                </div>
            </div>
            <div className="mt-6">{children}</div>
        </StudentGroupEnrolmentManagementContext.Provider>
    );
}
