import { StudentGroupDisciplineViewModel } from '@/types/view-models/student-group-discipline.view-model';
import React from 'react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import { useForm } from '@inertiajs/react';
import Spinner from '@/Components/Spinner';
import { BookOpenIcon } from '@heroicons/react/24/outline';

interface StudentGroupDisciplineEntryProps {
    group: StudentGroupViewModel;
    discipline: StudentGroupDisciplineViewModel;
}

export default function StudentGroupDisciplineEntry({
    group,
    discipline,
}: StudentGroupDisciplineEntryProps) {
    const { processing: deleting, delete: destroy } = useForm();

    function removeDiscipline(): void {
        if (deleting) {
            throw new Error('Already deleting');
        }

        destroy(
            route('student_groups.disciplines.delete', {
                group: group.id,
                discipline: discipline.id,
            })
        );
    }

    return (
        <li className="flex items-center justify-between py-3">
            <div className="flex items-center">
                {/* Icon */}
                <span className="flex shrink-0 size-8 items-center justify-center rounded-full border border-gray-800 text-gray-900 transition-colors">
                    <BookOpenIcon className="size-5" aria-hidden="true" />
                </span>

                {/* Name */}
                <p className="ml-4 text-sm font-medium text-gray-900">
                    {discipline.name}
                </p>
            </div>

            {/* Delete action */}
            {deleting ? (
                <Spinner className="size-6" />
            ) : (
                <button
                    type="button"
                    onClick={() => {
                        removeDiscipline();
                    }}
                    className="ml-6 rounded-md bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Remove
                    <span className="sr-only"> {discipline.name}</span>
                </button>
            )}
        </li>
    );
}
