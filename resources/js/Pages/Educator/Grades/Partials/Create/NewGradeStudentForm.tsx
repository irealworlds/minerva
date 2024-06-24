import { combineClassNames } from '@/utils/combine-class-names.function';
import InstitutionSelector from '@/Pages/Educator/Grades/Partials/Create/Selectors/InstitutionSelector';
import { InstitutionDto } from '@/types/dtos/educator/institution.dto';
import StudentGroupSelector from '@/Pages/Educator/Grades/Partials/Create/Selectors/StudentGroupSelector';
import { StudentGroupDto } from '@/types/dtos/educator/student-group.dto';
import React from 'react';
import { DisciplineDto } from '@/types/dtos/educator/discipline.dto';
import DisciplineSelector from '@/Pages/Educator/Grades/Partials/Create/Selectors/DisciplineSelector';
import StudentDisciplineEnrolmentSelector from '@/Pages/Educator/Grades/Partials/Create/Selectors/StudentDisciplineEnrolmentSelector';
import { StudentDisciplineEnrolmentDto } from '@/types/dtos/educator/student-discipline-enrolment.dto';

interface NewGradeStudentFormProps {
    className?: string;
    errors: Partial<Record<keyof GradeStudentFormData, string>>;
    disabled?: boolean;

    selectedInstitution: InstitutionDto | null;
    setSelectedInstitution: (value: InstitutionDto | null) => void;

    selectedStudentGroup: StudentGroupDto | null;
    setSelectedStudentGroup: (value: StudentGroupDto | null) => void;

    selectedDiscipline: DisciplineDto | null;
    setSelectedDiscipline: (value: DisciplineDto | null) => void;

    selectedStudentDisciplineEnrolment: StudentDisciplineEnrolmentDto | null;
    setSelectedStudentDisciplineEnrolment: (
        value: StudentDisciplineEnrolmentDto | null
    ) => void;
}

interface GradeStudentFormData {
    studentKey: string;
    studentGroupKey: string;
    disciplineKey: string;
}

export default function NewGradeStudentForm({
    className,
    disabled,
    errors,
    selectedInstitution,
    setSelectedInstitution,
    selectedStudentGroup,
    setSelectedStudentGroup,
    selectedDiscipline,
    setSelectedDiscipline,
    selectedStudentDisciplineEnrolment,
    setSelectedStudentDisciplineEnrolment,
}: NewGradeStudentFormProps) {
    return (
        <div
            className={combineClassNames(
                'bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl',
                className
            )}>
            <div className="px-4 py-6 sm:p-8">
                <div className="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    {/* Institution */}
                    <div className="sm:col-span-4">
                        <InstitutionSelector
                            value={selectedInstitution}
                            onChange={setSelectedInstitution}
                            disabled={disabled}
                        />
                    </div>

                    {/* Student Group */}
                    <div className="sm:col-span-4">
                        <StudentGroupSelector
                            value={selectedStudentGroup}
                            onChange={setSelectedStudentGroup}
                            institutionKey={selectedInstitution?.id}
                            disabled={!!disabled || !selectedInstitution}
                            errors={errors.studentGroupKey}
                        />
                    </div>

                    {/* Discipline */}
                    <div className="sm:col-span-4">
                        <DisciplineSelector
                            value={selectedDiscipline}
                            onChange={setSelectedDiscipline}
                            studentGroupKey={selectedStudentGroup?.id}
                            disabled={!!disabled || !selectedStudentGroup}
                            errors={errors.disciplineKey}
                        />
                    </div>

                    {/* Student discipline enrolment */}
                    <div className="sm:col-span-4">
                        <StudentDisciplineEnrolmentSelector
                            value={selectedStudentDisciplineEnrolment}
                            onChange={setSelectedStudentDisciplineEnrolment}
                            disciplineKey={selectedDiscipline?.id}
                            studentGroupKey={selectedStudentGroup?.id}
                            disabled={
                                !!disabled ||
                                !selectedStudentGroup ||
                                !selectedDiscipline
                            }
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
