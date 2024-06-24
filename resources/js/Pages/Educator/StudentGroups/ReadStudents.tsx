import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import ReadStudentGroupLayout from '@/Pages/Educator/StudentGroups/Partials/Read/ReadStudentGroupLayout';
import DisciplineSelector from '@/Pages/Educator/StudentGroups/Partials/Read/Students/DisciplineSelector';
import { useEffect, useRef, useState } from 'react';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import DisciplineStudentsTable from '@/Pages/Educator/StudentGroups/Partials/Read/Students/DisciplineStudentsTable';
import DisciplineNotSelected from '@/Pages/Educator/StudentGroups/Partials/Read/Students/DisciplineNotSelected';
import NoDisciplines from '@/Pages/Educator/StudentGroups/Partials/Read/Students/NoDisciplines';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { EducatorTaughtStudentViewModel } from '@/types/view-models/educator/educator-taught-student.view-model';
import NoStudents from '@/Pages/Educator/StudentGroups/Partials/Read/Students/NoStudents';

type ReadStudentsPageProps = PageProps<{
    studentGroup: {
        id: string;
        name: string;
    };
    disciplines: DisciplineDto[];
    students: PaginatedCollection<EducatorTaughtStudentViewModel>;
    initialDisciplineKey: string | null;
}>;

export default function ReadStudents({
    auth,
    studentGroup,
    disciplines,
    students,
    initialDisciplineKey,
}: ReadStudentsPageProps) {
    const [selectedDiscipline, setSelectedDiscipline] =
        useState<DisciplineDto | null>(null);
    const initialMount = useRef(true);

    useEffect(() => {
        if (initialDisciplineKey) {
            if (initialDisciplineKey !== selectedDiscipline?.id) {
                const discipline = disciplines.find(
                    d => d.id === initialDisciplineKey
                );
                if (discipline) {
                    setSelectedDiscipline(discipline);
                }
            }
        } else {
            setSelectedDiscipline(null);
        }
    }, [initialDisciplineKey]);

    useEffect(() => {
        if (initialMount.current) {
            initialMount.current = false;
            return;
        }

        if (selectedDiscipline?.id === initialDisciplineKey) {
            return;
        }

        router.visit(
            route('educator.studentGroups.read.students', {
                studentGroup: studentGroup.id,
                disciplineKey: selectedDiscipline?.id,
            }),
            {
                only: ['students', 'initialDisciplineKey'],
            }
        );
    }, [selectedDiscipline]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="ReadStudents" />

            <ReadStudentGroupLayout studentGroup={studentGroup}>
                <div className="mt-8 flow-root container mx-auto">
                    {disciplines.length > 0 ? (
                        selectedDiscipline ? (
                            <>
                                {students.total > 0 ? (
                                    <>
                                        <div className="flex">
                                            <DisciplineSelector
                                                className="w-full max-w-xs"
                                                disciplines={disciplines}
                                                value={selectedDiscipline}
                                                onChange={setSelectedDiscipline}
                                            />
                                        </div>
                                        <DisciplineStudentsTable
                                            students={students}
                                            studentGroupKey={studentGroup.id}
                                            disciplineKey={
                                                selectedDiscipline.id
                                            }
                                        />
                                    </>
                                ) : (
                                    <NoStudents
                                        disciplines={disciplines}
                                        value={selectedDiscipline}
                                        onChange={setSelectedDiscipline}
                                        disciplineName={selectedDiscipline.name}
                                        studentGroupName={studentGroup.name}
                                    />
                                )}
                            </>
                        ) : (
                            <DisciplineNotSelected
                                disciplines={disciplines}
                                value={selectedDiscipline}
                                onChange={setSelectedDiscipline}
                            />
                        )
                    ) : (
                        <NoDisciplines />
                    )}
                </div>
            </ReadStudentGroupLayout>
        </AuthenticatedLayout>
    );
}
