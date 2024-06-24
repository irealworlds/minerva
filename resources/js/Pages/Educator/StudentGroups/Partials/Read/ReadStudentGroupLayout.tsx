import { combineClassNames } from '@/utils/combine-class-names.function';
import { PropsWithChildren } from 'react';
import { Link } from '@inertiajs/react';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';

interface ReadStudentGroupLayoutProps extends PropsWithChildren {
    studentGroup: {
        id: string;
        name: string;
    };
}

export default function ReadStudentGroupLayout({
    children,
    studentGroup,
}: ReadStudentGroupLayoutProps) {
    const tabs = [
        {
            name: 'General information',
            href: route('educator.studentGroups.read.general', {
                studentGroup: studentGroup.id,
            }),
            current: route().current('educator.studentGroups.read.general'),
        },
        {
            name: 'Students',
            href: route('educator.studentGroups.read.students', {
                studentGroup: studentGroup.id,
            }),
            current: route().current('educator.studentGroups.read.students'),
        },
    ];
    return (
        <>
            <div className="border-b border-gray-200 pb-5 sm:pb-0">
                <div className="flex items-center gap-2">
                    <Link href={route('educators.studentGroups.list')}>
                        <ArrowLeftIcon className="size-4" />
                    </Link>
                    <h3 className="text-base font-semibold leading-6 text-gray-900">
                        {studentGroup.name}
                    </h3>
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
        </>
    );
}
