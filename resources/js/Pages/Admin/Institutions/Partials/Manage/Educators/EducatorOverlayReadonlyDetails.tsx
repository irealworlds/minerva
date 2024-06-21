import { InstitutionEducatorViewModel } from '@/types/view-models/institution-educator.view-model';
import EducatorDetailsRoleEntry from '@/Pages/Admin/Institutions/Partials/Manage/Educators/EducatorDetailsRoleEntry';
import { EducatorTaughtDisciplineDto } from '@/types/dtos/educator-taught-discipline.dto';
import EducatorTaughtDisciplineEntry from '@/Pages/Admin/Institutions/Partials/Manage/Educators/EducatorTaughtDisciplineEntry';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface EducatorOverlayReadonlyDetailsProps {
    educator: InstitutionEducatorViewModel;
    setCurrentSection: (newSection: 'add-discipline' | 'add-roles') => void;
    disciplines: EducatorTaughtDisciplineDto[] | undefined;
}

export default function EducatorOverlayReadonlyDetails({
    educator,
    setCurrentSection,
    disciplines,
}: EducatorOverlayReadonlyDetailsProps) {
    return (
        <dl className="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    E-mail address
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                    {educator.email}
                </dd>
            </div>
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    Roles
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0 grow">
                    <div className={educator.roles.length > 0 ? '-mt-3' : ''}>
                        <ul
                            role="list"
                            className="divide-y divide-gray-100 text-sm leading-6">
                            {educator.roles.map((role, roleIdx) => (
                                <li key={roleIdx} className="py-3">
                                    <EducatorDetailsRoleEntry role={role} />
                                </li>
                            ))}
                        </ul>

                        <div
                            className={combineClassNames(
                                'flex',
                                educator.roles.length > 0
                                    ? 'border-t border-gray-100 pt-3'
                                    : ''
                            )}>
                            <button
                                type="button"
                                onClick={() => {
                                    setCurrentSection('add-roles');
                                }}
                                className="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                                <span aria-hidden="true" className="mr-2">
                                    +
                                </span>
                                Add another role
                            </button>
                        </div>
                    </div>
                </dd>
            </div>
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    Taught disciplines
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0 grow">
                    {disciplines === undefined ? (
                        <div
                            role="status"
                            className="max-w-md space-y-4 divide-y divide-gray-100 animate-pulse dark:divide-gray-700">
                            <div className="flex items-center justify-between">
                                <div>
                                    <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div className="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div className="flex items-center justify-between pt-4">
                                <div>
                                    <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div className="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div className="flex items-center justify-between pt-4">
                                <div>
                                    <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div className="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div className="flex items-center justify-between pt-4">
                                <div>
                                    <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div className="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <div className="flex items-center justify-between pt-4">
                                <div>
                                    <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-600 w-24 mb-2.5"></div>
                                    <div className="w-32 h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                                </div>
                                <div className="h-2.5 bg-gray-300 rounded-full dark:bg-gray-700 w-12"></div>
                            </div>
                            <span className="sr-only">Loading...</span>
                        </div>
                    ) : (
                        <div className={disciplines.length > 0 ? '-mt-3' : ''}>
                            <ul
                                role="list"
                                className="divide-y divide-gray-100 text-sm leading-6">
                                {disciplines
                                    .sort((a, b) =>
                                        a.studentGroupKey.localeCompare(
                                            b.studentGroupKey
                                        )
                                    )
                                    .map((discipline, index) => (
                                        <li key={index} className="py-3">
                                            <EducatorTaughtDisciplineEntry
                                                educator={educator}
                                                discipline={discipline}
                                            />
                                        </li>
                                    ))}
                            </ul>

                            <div
                                className={combineClassNames(
                                    'flex',
                                    disciplines.length > 0
                                        ? 'border-t border-gray-100 pt-3'
                                        : ''
                                )}>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setCurrentSection('add-discipline');
                                    }}
                                    className="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                                    <span aria-hidden="true" className="mr-2">
                                        +
                                    </span>
                                    Add another discipline
                                </button>
                            </div>
                        </div>
                    )}
                </dd>
            </div>
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    Joined
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                    <time dateTime={new Date(educator.createdAt).toISOString()}>
                        {new Date(educator.createdAt).toLocaleDateString(
                            undefined,
                            {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                            }
                        )}
                    </time>
                </dd>
            </div>
        </dl>
    );
}
