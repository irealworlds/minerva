import { combineClassNames } from '@/utils/combine-class-names.function';
import { GroupedDisciplineEnrolment } from '@/Pages/Student/Enrolments/Partials/Read/Disciplines/DisciplinesList';

interface DisciplineCardProps {
    className?: string;
    discipline: GroupedDisciplineEnrolment;
}

export default function DisciplineCard({
    className,
    discipline,
}: DisciplineCardProps) {
    return (
        <div
            className={combineClassNames(
                className,
                'overflow-hidden rounded-xl border border-gray-200'
            )}>
            <div className="flex items-center gap-x-4 border-b border-gray-900/5 bg-gray-100 p-6">
                <img
                    src={discipline.disciplinePictureUri}
                    alt={discipline.disciplineName}
                    className="h-12 w-12 flex-none rounded-lg bg-white object-cover ring-1 ring-gray-900/10"
                />
                <div className="text-sm font-medium leading-6 text-gray-900">
                    {discipline.disciplineAbbreviation ??
                        discipline.disciplineName}
                </div>
            </div>
            <dl className="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm leading-6">
                {/* Discipline name */}
                <div className="flex justify-between gap-x-4 py-3">
                    <dt className="text-gray-500">Full name</dt>
                    <dd className="text-gray-700">
                        {discipline.disciplineName}
                    </dd>
                </div>

                {/* Educator name */}
                <div className="flex justify-between gap-x-4 py-3">
                    <dt className="text-gray-500">
                        {discipline.educators.length === 1
                            ? 'Educator'
                            : 'Educators'}
                    </dt>
                    <dd className="text-gray-700">
                        <ul role="list" className="space-y-3">
                            {discipline.educators.map(e => (
                                <li
                                    key={e.key}
                                    className="flex items-center justify-between">
                                    <div className="flex items-center">
                                        <img
                                            src={e.pictureUri}
                                            alt=""
                                            className="size-7 rounded-full"
                                        />
                                        <p className="ml-2 text-sm text-gray-900">
                                            {e.name}
                                        </p>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    </dd>
                </div>

                {/* Average */}
                <div className="flex justify-between gap-x-4 py-3">
                    <dt className="text-gray-500">Average grade</dt>
                    <dd className="text-gray-700">
                        {discipline.averageGrade === null ? (
                            <span>n/a</span>
                        ) : (
                            <>
                                <span className="font-semibold">
                                    {discipline.averageGrade.toLocaleString()}
                                </span>{' '}
                                <span className="text-gray-500">
                                    of {discipline.gradesCount.toLocaleString()}{' '}
                                    {discipline.gradesCount === 1
                                        ? 'grade'
                                        : 'grade'}
                                </span>
                            </>
                        )}
                    </dd>
                </div>
            </dl>
        </div>
    );
}
