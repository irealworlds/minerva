import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/admin/student-discipline-enrolment.view-model';
import DisciplineCard from '@/Pages/Student/Enrolments/Partials/Read/Disciplines/DisciplineCard';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface DisciplinesListProps {
    className?: string;
    disciplines: StudentDisciplineEnrolmentViewModel[];
}

export default function DisciplinesList({
    className,
    disciplines,
}: DisciplinesListProps) {
    return (
        <ul
            role="list"
            className={combineClassNames(
                className,
                'grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 xl:gap-x-8'
            )}>
            {disciplines.map(discipline => (
                <li key={discipline.disciplineKey}>
                    <DisciplineCard
                        discipline={discipline}
                        className="h-full"
                    />
                </li>
            ))}
        </ul>
    );
}
