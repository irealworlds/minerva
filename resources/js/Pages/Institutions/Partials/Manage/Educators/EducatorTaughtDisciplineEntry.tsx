import { InstitutionEducatorViewModel } from '@/types/view-models/institution-educator.view-model';
import { EducatorTaughtDisciplineDto } from '@/types/dtos/educator-taught-discipline.dto';
import { useState } from 'react';
import Spinner from '@/Components/Spinner';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { router } from '@inertiajs/react';

interface EducatorTaughtDisciplineEntryProps {
    educator: InstitutionEducatorViewModel;
    discipline: EducatorTaughtDisciplineDto;
}

export default function EducatorTaughtDisciplineEntry({
    discipline,
}: EducatorTaughtDisciplineEntryProps) {
    const [deleting, setDeleting] = useState(false);

    function removeDiscipline() {
        router.delete(
            route('educators.studentGroupDisciplines.delete', {
                educator: discipline.educatorKey,
                studentGroup: discipline.studentGroupKey,
                discipline: discipline.disciplineKey,
            }),
            {
                preserveState: true,
                preserveScroll: true,
                onStart: () => {
                    setDeleting(true);
                },
                onFinish: () => {
                    setDeleting(false);
                },
            }
        );
    }

    return (
        <div className="flex justify-between gap-x-6">
            <div className={combineClassNames(deleting ? 'opacity-50' : '')}>
                <span className="font-medium text-gray-900">
                    {discipline.disciplineName}
                </span>{' '}
                <span className="text-gray-500">
                    for {discipline.studentGroupName}
                </span>
            </div>

            {/* Removal button */}
            {deleting ? (
                <Spinner className="size-4" />
            ) : (
                <button
                    type="button"
                    className="text-red-600 hover:text-red-500"
                    onClick={() => {
                        removeDiscipline();
                    }}>
                    Remove
                </button>
            )}
        </div>
    );
}
