import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import ManageStudentLayout from '@/Pages/Educator/Students/Partials/ManageStudentLayout';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/educator/student-discipline-enrolment.view-model';
import { useMemo } from 'react';

type ManageOverviewPageProps = PageProps<{
    student: {
        key: string;
        name: string;
        pictureUri: string;
    };
    taughtDisciplines: StudentDisciplineEnrolmentViewModel[];
}>;

export default function ManageOverview({
    auth,
    student,
    taughtDisciplines,
}: ManageOverviewPageProps) {
    const uniqueTaughtDisciplines = useMemo(() => {
        const result = new Map<
            string,
            {
                disciplineName: string;
                studentGroupNames: string[];
                enroledAt: string;
            }
        >();

        for (const taughtDiscipline of taughtDisciplines) {
            if (result.has(taughtDiscipline.disciplineKey)) {
                result
                    .get(taughtDiscipline.disciplineKey)
                    ?.studentGroupNames.push(taughtDiscipline.studentGroupName);
            } else {
                result.set(taughtDiscipline.disciplineKey, {
                    disciplineName: taughtDiscipline.disciplineName,
                    studentGroupNames: [taughtDiscipline.studentGroupName],
                    enroledAt: taughtDiscipline.enroledAt,
                });
            }
        }

        return result;
    }, [taughtDisciplines]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Manage student" />

            <ManageStudentLayout student={student}>
                <div className="">
                    <div className="bg-white shadow rounded-lg sm:col-span-2">
                        <div className="border-b border-gray-200 px-4 py-5 sm:px-6">
                            <h3 className="text-base font-semibold leading-6 text-gray-900">
                                Disciplines you teach
                            </h3>
                            <p className="mt-1 text-sm text-gray-500">
                                You teach {student.name}{' '}
                                {uniqueTaughtDisciplines.size}{' '}
                                {uniqueTaughtDisciplines.size === 1
                                    ? 'discipline'
                                    : 'disciplines'}
                                .
                            </p>
                        </div>

                        <ul
                            role="list"
                            className="divide-y divide-gray-100 px-4 py-5 sm:px-6">
                            {Array.from(uniqueTaughtDisciplines.values()).map(
                                (discipline, disciplineIdx) => (
                                    <li
                                        key={disciplineIdx}
                                        className="flex items-center justify-between gap-x-6 py-5">
                                        <div className="min-w-0">
                                            <div className="flex items-start gap-x-3">
                                                <p className="text-sm font-semibold leading-6 text-gray-900">
                                                    {discipline.disciplineName}
                                                </p>
                                            </div>
                                            <div className="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                                {/* Enrolment date */}
                                                <p className="whitespace-nowrap">
                                                    Since{' '}
                                                    <time
                                                        dateTime={new Date(
                                                            discipline.enroledAt
                                                        ).toISOString()}>
                                                        {new Date(
                                                            discipline.enroledAt
                                                        ).toLocaleDateString(
                                                            undefined,
                                                            {
                                                                month: 'long',
                                                                day: 'numeric',
                                                                year: 'numeric',
                                                            }
                                                        )}
                                                    </time>
                                                </p>
                                                {/* Divider */}
                                                <svg
                                                    viewBox="0 0 2 2"
                                                    className="h-0.5 w-0.5 fill-current">
                                                    <circle
                                                        cx={1}
                                                        cy={1}
                                                        r={1}
                                                    />
                                                </svg>

                                                {/* Student group(s) */}
                                                <p className="whitespace-nowrap">
                                                    In{' '}
                                                    {
                                                        discipline
                                                            .studentGroupNames
                                                            .length
                                                    }{' '}
                                                    student{' '}
                                                    {discipline
                                                        .studentGroupNames
                                                        .length === 1
                                                        ? 'group'
                                                        : 'groups'}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                )
                            )}
                        </ul>
                    </div>
                </div>
            </ManageStudentLayout>
        </AuthenticatedLayout>
    );
}
