import { PlusIcon } from '@heroicons/react/20/solid';
import React from 'react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import StudentGroupDisciplineEntry from '@/Pages/Institutions/Partials/Manage/Groups/StudentGroupDisciplineEntry';

interface StudentGroupDisciplinesListProps {
    group: StudentGroupViewModel;
    setModifyingSection: (section: 'addDiscipline' | null) => void;
}

export default function StudentGroupDisciplinesList({
    group,
    setModifyingSection,
}: StudentGroupDisciplinesListProps) {
    return (
        <div>
            <h3 className="font-medium text-gray-900">Studied disciplines</h3>
            <ul
                role="list"
                className="mt-2 divide-y divide-gray-200 border-b border-t border-gray-200">
                {/* Add new discipline */}
                <li className="flex items-center justify-between py-2">
                    <button
                        onClick={() => {
                            setModifyingSection('addDiscipline');
                        }}
                        type="button"
                        className="group w-full -ml-1 flex items-center rounded-md bg-white p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span className="flex size-8 items-center justify-center rounded-full border-2 border-dashed border-gray-300 text-gray-400 group-hover:border-gray-400 group-hover:text-gray-500 transition-colors">
                            <PlusIcon className="size-5" aria-hidden="true" />
                        </span>
                        <div className="ml-4 text-left">
                            <p className="text-sm font-medium text-indigo-500 group-hover:text-indigo-600 transition-colors">
                                Add discipline
                            </p>
                            <p className="text-xs text-gray-400 group-hover:text-gray-500">
                                to this student group
                            </p>
                        </div>
                    </button>
                </li>

                {group.disciplines.map(discipline => (
                    <StudentGroupDisciplineEntry
                        key={discipline.id}
                        group={group}
                        discipline={discipline}
                    />
                ))}
            </ul>
        </div>
    );
}
