import {
    ArrowRightIcon,
    BuildingLibraryIcon,
    CalendarDaysIcon,
    FolderIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';
import { EducatorStudentGroupViewModel } from '@/types/view-models/educator/educator-student-group.view-model';
import React from 'react';
import { Link } from '@inertiajs/react';

interface StudentGroupCardProps {
    studentGroup: EducatorStudentGroupViewModel;
}

export default function StudentGroupCard({
    studentGroup,
}: StudentGroupCardProps) {
    return (
        <div>
            <h2 className="sr-only">Summary</h2>
            <div className="rounded-lg bg-white shadow-sm ring-1 ring-gray-900/5 w-full h-full">
                <div className="flex flex-col justify-between">
                    <div className="pl-6 pt-6">
                        <nav className="text-gray-500">
                            <ol className="flex flex-wrap items-center gap-x-1">
                                {studentGroup.ancestors.map(ancestor => (
                                    <li
                                        key={ancestor.id}
                                        className="flex items-center">
                                        <span className="mr-1 text-xs font-medium">
                                            {ancestor.name}
                                        </span>
                                        <svg
                                            className="size-3 flex-shrink-0"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                            aria-hidden="true">
                                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                        </svg>
                                    </li>
                                ))}
                            </ol>
                        </nav>
                        <h3 className="mt-1 text-base font-semibold leading-6 text-gray-900">
                            {studentGroup.name}
                        </h3>
                    </div>
                    <dl className="border-t border-gray-900/5 pt-4 mt-4 w-full space-y-4">
                        {/* Disciplines count */}
                        <div className="flex w-full flex-none gap-x-4 px-6">
                            <dt className="flex-none">
                                <span className="sr-only">
                                    Disciplines count
                                </span>
                                <FolderIcon
                                    className="size-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </dt>
                            <dd className="text-sm leading-6 text-gray-500">
                                {studentGroup.disciplinesCount.toLocaleString()}{' '}
                                {studentGroup.disciplinesCount === 1
                                    ? 'discipline'
                                    : 'disciplines'}
                            </dd>
                        </div>

                        {/* Students count */}
                        <div className="flex w-full flex-none gap-x-4 px-6">
                            <dt className="flex-none">
                                <span className="sr-only">Students count</span>
                                <UserGroupIcon
                                    className="size-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </dt>
                            <dd className="text-sm leading-6 text-gray-500">
                                {studentGroup.studentsCount.toLocaleString()}{' '}
                                {studentGroup.studentsCount === 1
                                    ? 'student'
                                    : 'students'}
                            </dd>
                        </div>

                        {/* Institution */}
                        <div className="flex w-full flex-none gap-x-4 px-6">
                            <dt className="flex-none">
                                <span className="sr-only">Students count</span>
                                <BuildingLibraryIcon
                                    className="size-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </dt>
                            <dd className="text-sm leading-6 text-gray-500">
                                <Link
                                    href={route('public.institutions.show', {
                                        institution:
                                            studentGroup.institution.id,
                                    })}
                                    className="text-sm leading-6 text-indigo-600 hover:text-indigo-500">
                                    {studentGroup.institution.name}
                                </Link>
                            </dd>
                        </div>

                        {/* Teaching since */}
                        <div className="flex w-full flex-none gap-x-4 px-6">
                            <dt className="flex-none">
                                <span className="sr-only">Teaching since</span>
                                <CalendarDaysIcon
                                    className="size-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </dt>
                            <dd className="text-sm leading-6 text-gray-500">
                                <time
                                    dateTime={new Date(
                                        studentGroup.teachingSince
                                    ).toISOString()}>
                                    {new Date(
                                        studentGroup.teachingSince
                                    ).toLocaleDateString(undefined, {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric',
                                    })}
                                </time>
                            </dd>
                        </div>
                    </dl>
                </div>
                <div className="mt-6 border-t border-gray-900/5 px-6 py-6">
                    <Link
                        href={route('educator.studentGroups.read.general', {
                            studentGroup: studentGroup.id,
                        })}
                        className="text-sm font-semibold leading-6 text-gray-900 flex items-center gap-2">
                        Manage{' '}
                        <ArrowRightIcon aria-hidden="true" className="size-3" />
                    </Link>
                </div>
            </div>
        </div>
    );
}
