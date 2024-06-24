import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import ReadStudentGroupLayout from '@/Pages/Educator/StudentGroups/Partials/Read/ReadStudentGroupLayout';

type ReadGeneralPageProps = PageProps<{
    studentsCount: number;
    disciplinesCount: number;
    averageGrade: number | null;
    averageGradesCount: number;
    studentGroup: {
        id: string;
        name: string;
    };
}>;

export default function ReadGeneral({
    auth,
    studentGroup,
    studentsCount,
    disciplinesCount,
    averageGrade,
    averageGradesCount,
}: ReadGeneralPageProps) {
    const stats = [
        {
            name: 'Students count',
            value: studentsCount.toLocaleString(),
        },
        {
            name: 'Disciplines count',
            value: disciplinesCount.toLocaleString(),
        },
        {
            name: 'Average grade',
            value:
                averageGrade === null ? 'n/a' : averageGrade.toLocaleString(),
        },
        {
            name: 'Average grades count',
            value: averageGradesCount.toLocaleString(),
        },
    ];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Student group" />

            <ReadStudentGroupLayout studentGroup={studentGroup}>
                <dl className="mx-auto grid grid-cols-1 gap-px shadow rounded-lg bg-gray-900/5 sm:grid-cols-2 lg:grid-cols-4">
                    {stats.map(stat => (
                        <div
                            key={stat.name}
                            className="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
                            <dt className="text-sm font-medium leading-6 text-gray-500">
                                {stat.name}
                            </dt>
                            <dd className="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-gray-900">
                                {stat.value}
                            </dd>
                        </div>
                    ))}
                </dl>
            </ReadStudentGroupLayout>
        </AuthenticatedLayout>
    );
}
