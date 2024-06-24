import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/student/student-discipline-enrolment.view-model';
import DisciplineCard from '@/Pages/Student/Enrolments/Partials/Read/Disciplines/DisciplineCard';
import { useMemo } from 'react';

interface DisciplinesListProps {
    disciplineEnrolments: StudentDisciplineEnrolmentViewModel[];
}

export interface GroupedDisciplineEnrolment {
    enrolmentKeys: string[];
    disciplineKey: string;
    disciplineName: string;
    disciplineAbbreviation: string | null;
    disciplinePictureUri: string;
    educators: {
        key: string;
        name: string;
        pictureUri: string;
    }[];
    averageGrade: number | null;
    gradesCount: number;
}

export default function DisciplinesList({
    disciplineEnrolments,
}: DisciplinesListProps) {
    const groupedDisciplines = useMemo(() => {
        return disciplineEnrolments.reduce((accumulator, current) => {
            if (accumulator.has(current.disciplineKey)) {
                accumulator
                    .get(current.disciplineKey)
                    ?.enrolmentKeys.push(current.key);
                accumulator.get(current.disciplineKey)?.educators.push({
                    key: current.educatorKey,
                    name: current.educatorName,
                    pictureUri: current.educatorPictureUri,
                });
            } else {
                accumulator.set(current.disciplineKey, {
                    enrolmentKeys: [current.key],
                    disciplineKey: current.disciplineKey,
                    disciplineName: current.disciplineName,
                    disciplineAbbreviation: current.disciplineAbbreviation,
                    disciplinePictureUri: current.disciplinePictureUri,
                    educators: [
                        {
                            key: current.educatorKey,
                            name: current.educatorName,
                            pictureUri: current.educatorPictureUri,
                        },
                    ],
                    averageGrade: current.averageGrade,
                    gradesCount: current.gradesCount,
                });
            }

            return accumulator;
        }, new Map<string, GroupedDisciplineEnrolment>());
    }, [disciplineEnrolments]);

    return (
        <ul
            role="list"
            className="grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-3 xl:gap-x-8">
            {Array.from(groupedDisciplines.values()).map(discipline => (
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
