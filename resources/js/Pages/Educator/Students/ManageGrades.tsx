import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import ManageStudentLayout from '@/Pages/Educator/Students/Partials/ManageStudentLayout';
import { StudentDisciplineEnrolmentViewModel } from '@/types/view-models/educator/student-discipline-enrolment.view-model';
import { useEffect, useMemo, useRef, useState } from 'react';
import DisciplineNotSelected from '@/Pages/Educator/Students/Partials/DisciplineNotSelected';
import NoDisciplines from '@/Pages/Educator/Students/Partials/NoDisciplines';
import GradesList from '@/Pages/Educator/Students/Partials/GradesList';
import DisciplineSelector from '@/Pages/Educator/Students/Partials/DisciplineSelector';
import { StudentGradeViewModel } from '@/types/view-models/educator/student-grade.view-model';
import NoGrades from '@/Pages/Educator/Students/Partials/NoGrades';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { PlusIcon } from '@heroicons/react/20/solid';

type ManageGradesPageProps = PageProps<{
    student: {
        key: string;
        name: string;
        pictureUri: string;
    };
    taughtDisciplines: StudentDisciplineEnrolmentViewModel[];
    selectedDisciplineKey: string;
    grades: StudentGradeViewModel[] | null;
}>;

export default function ManageGrades({
    auth,
    student,
    taughtDisciplines,
    selectedDisciplineKey,
    grades,
}: ManageGradesPageProps) {
    const initialMount = useRef(true);

    const managingDiscipline = useMemo(() => {
        if (selectedDisciplineKey) {
            const discipline = taughtDisciplines.find(
                d => d.disciplineKey === selectedDisciplineKey
            );
            if (discipline) {
                return discipline;
            }
        }

        return null;
    }, [selectedDisciplineKey]);

    const [selectedDiscipline, setSelectedDiscipline] =
        useState<StudentDisciplineEnrolmentViewModel | null>(
            managingDiscipline
        );

    useEffect(() => {
        if (initialMount.current) {
            initialMount.current = false;
            return;
        }

        if (selectedDiscipline?.disciplineKey === selectedDisciplineKey) {
            return;
        }

        router.visit(
            route('educator.students.manage.grades', {
                student: student.key,
                disciplineKey: selectedDiscipline?.disciplineKey,
            }),
            {
                only: [],
            }
        );
    }, [selectedDiscipline]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Manage student grades" />

            <ManageStudentLayout student={student}>
                <div className="mt-8 flow-root container mx-auto">
                    {taughtDisciplines.length > 0 ? (
                        managingDiscipline ? (
                            grades?.length ? (
                                <>
                                    <div className="flex items-center gap-3">
                                        <DisciplineSelector
                                            className="w-full max-w-xs"
                                            disciplines={taughtDisciplines}
                                            value={selectedDiscipline}
                                            onChange={setSelectedDiscipline}
                                            disabled={
                                                managingDiscipline !==
                                                selectedDiscipline
                                            }
                                        />
                                        <Link
                                            className="shrink-0"
                                            href={route(
                                                'educator.grades.create',
                                                {
                                                    studentKey: student.key,
                                                    disciplineKey:
                                                        managingDiscipline.disciplineKey,
                                                    studentGroupKey:
                                                        managingDiscipline.studentGroupKey,
                                                }
                                            )}>
                                            <PrimaryButton>
                                                <PlusIcon className="size-4 mr-2" />
                                                Add grade
                                            </PrimaryButton>
                                        </Link>
                                    </div>
                                    <GradesList
                                        className="mt-6"
                                        grades={grades}
                                    />
                                </>
                            ) : (
                                <NoGrades
                                    disciplines={taughtDisciplines}
                                    value={selectedDiscipline}
                                    onChange={setSelectedDiscipline}
                                    disabled={
                                        managingDiscipline !==
                                        selectedDiscipline
                                    }
                                    studentKey={student.key}
                                    disciplineKey={
                                        managingDiscipline.disciplineKey
                                    }
                                    studentGroupKey={
                                        managingDiscipline.studentGroupKey
                                    }
                                />
                            )
                        ) : (
                            <DisciplineNotSelected
                                disciplines={taughtDisciplines}
                                value={selectedDiscipline}
                                onChange={setSelectedDiscipline}
                                disabled={
                                    managingDiscipline !== selectedDiscipline
                                }
                            />
                        )
                    ) : (
                        <NoDisciplines />
                    )}
                </div>
            </ManageStudentLayout>
        </AuthenticatedLayout>
    );
}
