import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { StudentEnrolmentActivityItemViewModel } from '@/types/view-models/student/student-enrolment-activity-item.view-model';
import ActivityListCard from '@/Pages/Student/Enrolments/Partials/Read/Overview/ActivityListCard';

type ReadOverviewPageProps = PageProps<{
    enrolment: StudentGroupEnrolmentViewModel;
    activities: StudentEnrolmentActivityItemViewModel[];
    statsData: {
        studentsCount: number;
        disciplineCount: number;
        educatorsCount: number;
        averageGrade: number | null;
    };
}>;

export default function ReadOverview({
    auth,
    enrolment,
    activities,
    statsData,
}: ReadOverviewPageProps) {
    const secondaryNavigation = [
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

    const stats = [
        {
            name: 'Number of students',
            value: statsData.studentsCount.toLocaleString(),
        },
        {
            name: 'Number of disciplines',
            value: statsData.disciplineCount.toLocaleString(),
        },
        {
            name: 'Number of educators',
            value: statsData.educatorsCount.toLocaleString(),
        },
        {
            name: 'Average grade',
            value:
                statsData.averageGrade === null
                    ? 'n/a'
                    : statsData.averageGrade.toLocaleString(),
        },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Overview" />

            <div>
                <header>
                    {/* Secondary navigation */}
                    <nav className="flex overflow-x-auto border-b border-white/10 py-4">
                        <ul
                            role="list"
                            className="flex min-w-full flex-none gap-x-6 text-sm font-semibold leading-6 text-gray-500 sm:px-2">
                            {secondaryNavigation.map(item => (
                                <li key={item.name}>
                                    <Link
                                        href={item.href}
                                        className={
                                            item.current
                                                ? 'text-indigo-600'
                                                : ''
                                        }>
                                        {item.name}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </nav>

                    {/* Heading */}
                    <div className="rounded-t-lg flex flex-col items-start justify-between gap-x-8 gap-y-4 bg-gray-900 px-4 py-4 sm:flex-row sm:items-center sm:px-6 lg:px-8">
                        <div>
                            <div className="flex items-center gap-x-3">
                                <Link
                                    href={route('student.enrolments.list')}
                                    className="text-white">
                                    <ArrowLeftIcon className="size-4" />
                                </Link>
                                <h1 className="flex gap-x-3 text-base leading-7">
                                    <span className="font-semibold text-white">
                                        {enrolment.institutionName}
                                    </span>
                                    <span className="text-gray-600">/</span>
                                    <span className="font-semibold text-white">
                                        {enrolment.studentGroupName}
                                    </span>
                                </h1>
                            </div>
                            <p className="mt-2 text-xs leading-6 text-gray-400">
                                Your enrolment in a student group of{' '}
                                <span className="font-medium">
                                    {enrolment.institutionAncestors[0]?.name ??
                                        enrolment.institutionName}
                                </span>
                                .
                            </p>
                        </div>
                        <div className="order-first flex-none rounded-full bg-indigo-400/10 px-2 py-1 text-xs font-medium text-indigo-400 ring-1 ring-inset ring-indigo-400/30 sm:order-none">
                            Currently enroled
                        </div>
                    </div>

                    {/* Stats */}
                    <div className="rounded-b-lg grid grid-cols-1 bg-gray-800 sm:grid-cols-2 lg:grid-cols-4">
                        {stats.map((stat, statIdx) => (
                            <div
                                key={stat.name}
                                className={combineClassNames(
                                    statIdx % 2 === 1
                                        ? 'sm:border-l'
                                        : statIdx === 2
                                          ? 'lg:border-l'
                                          : '',
                                    'border-t border-white/5 px-4 py-6 sm:px-6 lg:px-8'
                                )}>
                                <p className="text-sm font-medium leading-6 text-gray-400">
                                    {stat.name}
                                </p>
                                <p className="mt-2 flex items-baseline gap-x-2">
                                    <span className="text-4xl font-semibold tracking-tight text-white">
                                        {stat.value}
                                    </span>
                                </p>
                            </div>
                        ))}
                    </div>
                </header>

                {/* Activity list */}
                <ActivityListCard className="mt-11" activities={activities} />
            </div>
        </AuthenticatedLayout>
    );
}
