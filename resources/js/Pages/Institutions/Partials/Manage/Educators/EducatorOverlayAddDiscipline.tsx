import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import StudentGroupSelector from '@/Pages/Institutions/Components/StudentGroupSelector';
import { FormEventHandler, useState } from 'react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import StudentGroupDisciplinesSelector from '@/Pages/Institutions/Components/StudentGroupDisciplinesSelector';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { useForm } from '@inertiajs/react';

interface EducatorOverlayAddDisciplineProps {
    educatorId: string;
    parentInstitutionId: string;
    setCurrentSection: (newSection: 'readonly') => void;
}

export default function EducatorOverlayAddDiscipline({
    educatorId,
    setCurrentSection,
    parentInstitutionId,
}: EducatorOverlayAddDisciplineProps) {
    const [studentGroup, setStudentGroup] =
        useState<StudentGroupViewModel | null>(null);

    const { data, setData, post, processing } = useForm({
        studentGroupKey: '',
        disciplineKeys: [] as string[],
    });

    const submit: FormEventHandler = e => {
        post(
            route('educators.disciplines.store', {
                educator: educatorId,
            })
        );
        e.preventDefault();
    };

    return (
        <div className="sm:px-6 sm:py-5">
            {/* Back to details */}
            <button
                type="button"
                onClick={() => {
                    setCurrentSection('readonly');
                }}
                className="flex items-center gap-2 leading-6 text-gray-500 font-semibold text-sm">
                <ArrowLeftIcon className="size-4" />
                <span>Back to details</span>
            </button>

            <form onSubmit={submit} className="space-y-8 mt-6">
                {/* Select student group */}
                <StudentGroupSelector
                    value={studentGroup}
                    onChange={newValue => {
                        setStudentGroup(newValue);
                        setData('studentGroupKey', newValue?.id ?? '');
                    }}
                    parentInstitutionId={parentInstitutionId}
                />

                {/* Disciplines */}
                {data.studentGroupKey.length > 0 && (
                    <StudentGroupDisciplinesSelector
                        studentGroupId={data.studentGroupKey}
                        onChange={newValue => {
                            setData(
                                'disciplineKeys',
                                newValue.map(d => d.id)
                            );
                        }}
                    />
                )}

                <div className="flex items-center justify-end gap-2">
                    <PrimaryButton disabled={processing} type="submit">
                        {processing ? 'Adding disciplines' : 'Add disciplines'}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    );
}
