import DisciplineSelector from '@/Pages/Educator/StudentGroups/Partials/Read/Students/DisciplineSelector';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import { AcademicCapIcon } from '@heroicons/react/24/outline';

interface DisciplineNotSelectedProps {
    value: DisciplineDto | null;
    onChange: (newValue: DisciplineDto | null) => void;
    disciplines: DisciplineDto[];
}

export default function DisciplineNotSelected({
    value,
    onChange,
    disciplines,
}: DisciplineNotSelectedProps) {
    return (
        <div className="w-full max-w-sm mx-auto">
            <AcademicCapIcon className="mx-auto size-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900 text-center">
                Discipline not selected
            </h3>
            <p className="mt-1 text-sm text-gray-500 text-center">
                Select one of the disciplines you are teaching this student
                groups to manage enroled students.
            </p>
            <div className="mt-6 flex justify-center">
                <DisciplineSelector
                    className="w-full"
                    disciplines={disciplines}
                    value={value}
                    onChange={onChange}
                />
            </div>
        </div>
    );
}
