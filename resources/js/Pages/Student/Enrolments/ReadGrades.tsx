import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import ReadLayout from '@/Pages/Student/Enrolments/Partials/Read/ReadLayout';
import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import GradesList from '@/Pages/Student/Enrolments/Partials/Read/Grades/GradesList';
import NoGrades from '@/Pages/Student/Enrolments/Partials/Read/Grades/NoGrades';
import DisciplineSelector from '@/Pages/Student/Enrolments/Components/DisciplineSelector';
import { useEffect, useState } from 'react';
import { DisciplineDto } from '@/types/dtos/student/discipline.dto';
import GradeDetails from '@/Pages/Student/Enrolments/Partials/Read/Grades/GradeDetails';
import { GradeDetailsViewModel } from '@/types/view-models/student/grade-details.view-model';
import { GradeViewModel } from '@/types/view-models/student/grade.view-model';

type ReadGradesPageProps = PageProps<{
    enrolment: StudentGroupEnrolmentViewModel;
    grades: PaginatedCollection<GradeViewModel>;
    filteredDiscipline: DisciplineDto | null;
    selectedGrade: GradeDetailsViewModel | null;
}>;

export default function ReadGrades({
    auth,
    enrolment,
    grades,
    filteredDiscipline,
    selectedGrade,
}: ReadGradesPageProps) {
    const [selectedDiscipline, setSelectedDiscipline] =
        useState<DisciplineDto | null>(filteredDiscipline);
    const [selectedGradeKey, setSelectedGradeKey] = useState<string | null>(
        selectedGrade?.gradeKey ?? null
    );
    useEffect(() => {
        if (
            filteredDiscipline?.id === selectedDiscipline?.id &&
            selectedGradeKey === (selectedGrade?.gradeKey ?? null)
        ) {
            return;
        }

        const queryParams: Record<string, string> = {};

        if (selectedDiscipline) {
            queryParams.disciplineKey = selectedDiscipline.id;
        }

        router.visit(
            route('student.enrolments.read.grades', {
                enrolment: enrolment.key,
                grade: selectedGradeKey,
                ...queryParams,
            }),
            {
                only: ['grades', 'filteredDiscipline', 'selectedGrade'],
            }
        );
    }, [selectedDiscipline, selectedGradeKey]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Grades" />

            <ReadLayout enrolment={enrolment}>
                <GradeDetails
                    grade={selectedGrade}
                    open={!!selectedGrade && !!selectedGradeKey}
                    onClose={() => {
                        setSelectedGradeKey(null);
                    }}
                />

                {grades.total === 0 ? (
                    <NoGrades />
                ) : (
                    <>
                        <DisciplineSelector
                            className="w-full max-w-xs"
                            value={selectedDiscipline}
                            onChange={setSelectedDiscipline}
                        />
                        <GradesList
                            className="mt-4"
                            grades={grades.data}
                            onGradeSelected={grade => {
                                setSelectedGradeKey(grade.gradeKey);
                            }}
                        />
                    </>
                )}
            </ReadLayout>
        </AuthenticatedLayout>
    );
}
