import { PropsWithChildren } from 'react';
import { Link } from '@inertiajs/react';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface ManageStudentLayoutProps extends PropsWithChildren {
    student: {
        key: string;
        name: string;
        pictureUri: string;
    };
}

export default function ManageStudentLayout({
    children,
    student,
}: ManageStudentLayoutProps) {
    const tabs = [
        {
            name: 'Overview',
            href: route('educator.students.manage.overview', {
                student: student.key,
            }),

            current: route().current('educator.students.manage.overview'),
        },
        {
            name: 'Grades',
            href: route('educator.students.manage.grades', {
                student: student.key,
            }),

            current: route().current('educator.students.manage.grades'),
        },
    ];

    return (
        <>
            <div className="md:flex md:items-center md:justify-between md:space-x-5 border-b border-gray-200 pb-5 sm:pb-0">
                <div className="flex items-start space-x-5">
                    {/* Picture */}
                    <div className="flex-shrink-0">
                        <div className="relative">
                            <img
                                className="size-16 rounded-full"
                                src={student.pictureUri}
                                alt=""
                            />
                            <span
                                className="absolute inset-0 rounded-full shadow-inner"
                                aria-hidden="true"
                            />
                        </div>
                    </div>

                    {/* Name and navigation */}
                    <div className="pt-1.5">
                        <h1 className="text-2xl font-bold text-gray-900">
                            {student.name}
                        </h1>

                        <p className="text-sm font-medium text-gray-500">
                            Student profile
                        </p>

                        {/* Tabs */}
                        <div>
                            <div className="mt-3 sm:mt-4">
                                <div className="sm:hidden">
                                    <label
                                        htmlFor="current-tab"
                                        className="sr-only">
                                        Select a tab
                                    </label>
                                    <select
                                        id="current-tab"
                                        name="current-tab"
                                        className="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                        defaultValue={
                                            tabs.find(tab => tab.current)?.name
                                        }>
                                        {tabs.map(tab => (
                                            <option key={tab.name}>
                                                {tab.name}
                                            </option>
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
                                                    tab.current
                                                        ? 'page'
                                                        : undefined
                                                }>
                                                {tab.name}
                                            </Link>
                                        ))}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Actions */}
                <div className="mt-6 flex flex-col-reverse justify-stretch space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-3 sm:space-y-0 sm:space-x-reverse md:mt-0 md:flex-row md:space-x-3"></div>
            </div>
            <div className="mt-6">{children}</div>
        </>
    );
}
