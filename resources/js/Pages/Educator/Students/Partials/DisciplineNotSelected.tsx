import { AcademicCapIcon } from '@heroicons/react/24/outline';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/educator/student-discipline-enrolment.view-model';
import DisciplineSelector from '@/Pages/Educator/Students/Partials/DisciplineSelector';

interface DisciplineNotSelectedProps {
    value: StudentDisciplineEnrolmentViewModel | null;
    onChange: (newValue: StudentDisciplineEnrolmentViewModel | null) => void;
    disciplines: StudentDisciplineEnrolmentViewModel[];
    disabled?: boolean;
}

export default function DisciplineNotSelected({
    value,
    onChange,
    disciplines,
    disabled,
}: DisciplineNotSelectedProps) {
    return (
        <div className="w-full max-w-sm mx-auto">
            <AcademicCapIcon className="mx-auto size-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900 text-center">
                Discipline not selected
            </h3>
            <p className="mt-1 text-sm text-gray-500 text-center">
                Select one of the disciplines you are teaching this student to
                manage their grades.
            </p>
            <div className="mt-6 flex justify-center">
                <DisciplineSelector
                    className="w-full"
                    disciplines={disciplines}
                    value={value}
                    onChange={onChange}
                    disabled={disabled}
                />
            </div>
        </div>
    );
}
