import GradeEntry from '@/Pages/Student/Enrolments/Partials/Read/Grades/GradeEntry';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { GradeViewModel } from '@/types/view-models/student/grade.view-model';

interface GradesListProps {
    className?: string;
    grades: GradeViewModel[];
    onGradeSelected: (grade: GradeViewModel) => void;
}

export default function GradesList({
    grades,
    className,
    onGradeSelected,
}: GradesListProps) {
    return (
        <ul
            role="list"
            className={combineClassNames(
                className,
                'divide-y divide-gray-100 overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl'
            )}>
            {grades.map(grade => (
                <li key={grade.gradeKey}>
                    <GradeEntry
                        grade={grade}
                        onSelected={() => {
                            onGradeSelected(grade);
                        }}
                    />
                </li>
            ))}
        </ul>
    );
}
