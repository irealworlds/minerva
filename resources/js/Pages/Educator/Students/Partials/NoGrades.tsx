import { AcademicCapIcon } from '@heroicons/react/24/outline';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/educator/student-discipline-enrolment.view-model';
import DisciplineSelector from '@/Pages/Educator/Students/Partials/DisciplineSelector';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { Link } from '@inertiajs/react';
import { PlusIcon } from '@heroicons/react/20/solid';

interface DisciplineNotSelectedProps {
    value: StudentDisciplineEnrolmentViewModel | null;
    onChange: (newValue: StudentDisciplineEnrolmentViewModel | null) => void;
    disciplines: StudentDisciplineEnrolmentViewModel[];
    disabled?: boolean;
    studentKey: string;
    disciplineKey: string;
    studentGroupKey: string;
}

export default function NoGrades({
    value,
    onChange,
    disciplines,
    disabled,
    studentKey,
    disciplineKey,
    studentGroupKey,
}: DisciplineNotSelectedProps) {
    return (
        <div className="w-full max-w-sm mx-auto">
            <AcademicCapIcon className="mx-auto size-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900 text-center">
                No grades
            </h3>
            <p className="mt-1 text-sm text-gray-500 text-center">
                This student has no grades for this discipline yet. Select
                another discipline or add a new grade.
            </p>
            <div className="mt-6 flex flex-col justify-center items-center gap-2">
                <DisciplineSelector
                    className="w-full"
                    disciplines={disciplines}
                    value={value}
                    onChange={onChange}
                    disabled={disabled}
                />
                <span className="text-gray-400 text-sm">or</span>
                <Link
                    className="shrink-0"
                    href={route('educator.grades.create', {
                        studentKey: studentKey,
                        disciplineKey: disciplineKey,
                        studentGroupKey: studentGroupKey,
                    })}>
                    <PrimaryButton>
                        <PlusIcon className="size-4 mr-2" />
                        Add grade
                    </PrimaryButton>
                </Link>
            </div>
        </div>
    );
}
