import { combineClassNames } from '@/utils/combine-class-names.function';
import NewGrade from '@/Pages/Educator/Students/Partials/Activity/NewGrade';
import { StudentGradeViewModel } from '@/types/view-models/educator/student-grade.view-model';

interface GradesListProps {
    className?: string;
    grades: StudentGradeViewModel[];
}

export default function GradesList({ className, grades }: GradesListProps) {
    return (
        <div
            className={combineClassNames(
                'flow-root bg-white p-6 rounded-lg shadow',
                className
            )}>
            <ul role="list" className="-mb-8">
                {grades.map((grade, gradeIdx) => (
                    <li key={grade.key}>
                        <div className="relative pb-8">
                            {gradeIdx !== grades.length - 1 ? (
                                <span
                                    className="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gray-200"
                                    aria-hidden="true"
                                />
                            ) : null}
                            <div className="relative flex items-start space-x-3">
                                <NewGrade
                                    awardedBy={grade.awardedBy}
                                    awardedPoints={grade.awardedPoints}
                                    maximumPoints={grade.maximumPoints}
                                    awardedAt={grade.awardedAt}
                                    notes={grade.notes}
                                />
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
