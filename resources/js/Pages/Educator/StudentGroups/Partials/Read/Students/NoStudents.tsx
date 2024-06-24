import { AcademicCapIcon } from '@heroicons/react/24/outline';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import DisciplineSelector from '@/Pages/Educator/StudentGroups/Partials/Read/Students/DisciplineSelector';

interface NoStudentsProps {
    value: DisciplineDto | null;
    onChange: (newValue: DisciplineDto | null) => void;
    disciplines: DisciplineDto[];
    disciplineName: string;
    studentGroupName: string;
}

export default function NoStudents({
    value,
    onChange,
    disciplines,
    disciplineName,
    studentGroupName,
}: NoStudentsProps) {
    return (
        <div className="w-full max-w-sm mx-auto">
            <AcademicCapIcon className="mx-auto size-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900 text-center">
                No students
            </h3>
            <p className="mt-1 text-sm text-gray-500 text-center">
                You are not currently teaching{' '}
                <span className="font-semibold">{disciplineName}</span> to any
                students enroled in student group{' '}
                <span className="font-semibold">{studentGroupName}</span>.
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
