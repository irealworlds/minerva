import { combineClassNames } from '@/utils/combine-class-names.function';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { EducatorTaughtStudentViewModel } from '@/types/view-models/educator/educator-taught-student.view-model';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/react';
import { ChevronDownIcon, PlusIcon } from '@heroicons/react/20/solid';
import { Link } from '@inertiajs/react';
import { AcademicCapIcon, Cog6ToothIcon } from '@heroicons/react/24/outline';

interface DisciplineStudentsTableProps {
    students: PaginatedCollection<EducatorTaughtStudentViewModel>;
    studentGroupKey: string;
    disciplineKey: string;
}

export default function DisciplineStudentsTable({
    students,
    studentGroupKey,
    disciplineKey,
}: DisciplineStudentsTableProps) {
    return (
        <div className="bg-white shadow rounded-lg mt-6">
            <div className="inline-block min-w-full py-2 align-middle">
                <table className="min-w-full border-separate border-spacing-0">
                    <thead>
                        <tr>
                            <th
                                scope="col"
                                className="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">
                                Name
                            </th>
                            <th
                                scope="col"
                                className="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">
                                Current average
                            </th>
                            <th
                                scope="col"
                                className="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter lg:table-cell">
                                Grades count
                            </th>
                            <th
                                scope="col"
                                className="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8">
                                <span className="sr-only">Manage</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {students.data.map((student, studentIdx) => (
                            <tr key={student.studentRegistrationId}>
                                <td
                                    className={combineClassNames(
                                        studentIdx !== students.data.length - 1
                                            ? 'border-b border-gray-200'
                                            : '',
                                        'whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8'
                                    )}>
                                    {student.studentName}
                                </td>
                                <td
                                    className={combineClassNames(
                                        studentIdx !== students.data.length - 1
                                            ? 'border-b border-gray-200'
                                            : '',
                                        'hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 sm:table-cell'
                                    )}>
                                    {student.currentAverage?.toLocaleString() ??
                                        'n/a'}
                                </td>
                                <td
                                    className={combineClassNames(
                                        studentIdx !== students.data.length - 1
                                            ? 'border-b border-gray-200'
                                            : '',
                                        'hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 lg:table-cell'
                                    )}>
                                    {student.gradesCount.toLocaleString()}
                                </td>
                                <td
                                    className={combineClassNames(
                                        studentIdx !== students.data.length - 1
                                            ? 'border-b border-gray-200'
                                            : '',
                                        'relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-8 lg:pr-8'
                                    )}>
                                    <Menu
                                        as="div"
                                        className="relative inline-block text-left">
                                        <div>
                                            <MenuButton className="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                Options
                                                <ChevronDownIcon
                                                    className="-mr-1 h-5 w-5 text-gray-400"
                                                    aria-hidden="true"
                                                />
                                            </MenuButton>
                                        </div>

                                        <MenuItems className="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 transition focus:outline-none data-[closed]:scale-95 data-[closed]:transform data-[closed]:opacity-0 data-[enter]:duration-100 data-[leave]:duration-75 data-[enter]:ease-out data-[leave]:ease-in">
                                            <div className="py-1">
                                                <MenuItem>
                                                    {({ focus }) => (
                                                        <Link
                                                            href={route(
                                                                'educator.students.manage.overview',
                                                                {
                                                                    student:
                                                                        student.studentRegistrationId,
                                                                    studentGroupKey,
                                                                    disciplineKey,
                                                                }
                                                            )}
                                                            className={combineClassNames(
                                                                focus
                                                                    ? 'bg-gray-100 text-gray-900'
                                                                    : 'text-gray-700',
                                                                'group flex items-center px-4 py-2 text-sm'
                                                            )}>
                                                            <Cog6ToothIcon
                                                                className="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                                aria-hidden="true"
                                                            />
                                                            Manage
                                                        </Link>
                                                    )}
                                                </MenuItem>
                                            </div>
                                            <div className="py-1">
                                                <MenuItem>
                                                    {({ focus }) => (
                                                        <Link
                                                            href={route(
                                                                'educator.students.manage.grades',
                                                                {
                                                                    student:
                                                                        student.studentRegistrationId,
                                                                    disciplineKey,
                                                                }
                                                            )}
                                                            className={combineClassNames(
                                                                focus
                                                                    ? 'bg-gray-100 text-gray-900'
                                                                    : 'text-gray-700',
                                                                'group flex items-center px-4 py-2 text-sm'
                                                            )}>
                                                            <AcademicCapIcon
                                                                className="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                                aria-hidden="true"
                                                            />
                                                            Manage grades
                                                        </Link>
                                                    )}
                                                </MenuItem>
                                                <MenuItem>
                                                    {({ focus }) => (
                                                        <Link
                                                            href={route(
                                                                'educator.grades.create',
                                                                {
                                                                    studentKey:
                                                                        student.studentRegistrationId,
                                                                    disciplineKey:
                                                                        disciplineKey,
                                                                    studentGroupKey:
                                                                        studentGroupKey,
                                                                }
                                                            )}
                                                            className={combineClassNames(
                                                                focus
                                                                    ? 'bg-gray-100 text-gray-900'
                                                                    : 'text-gray-700',
                                                                'group flex items-center px-4 py-2 text-sm'
                                                            )}>
                                                            <PlusIcon
                                                                className="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                                aria-hidden="true"
                                                            />
                                                            Add grade
                                                        </Link>
                                                    )}
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </Menu>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
