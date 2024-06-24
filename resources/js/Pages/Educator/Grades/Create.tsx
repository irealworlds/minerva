import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import NewGradeStudentForm from '@/Pages/Educator/Grades/Partials/Create/NewGradeStudentForm';
import { FormEventHandler, useEffect, useRef, useState } from 'react';
import NewGradeDetailsForm from '@/Pages/Educator/Grades/Partials/Create/NewGradeDetailsForm';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { InstitutionDto } from '@/types/dtos/educator/institution.dto';
import { StudentGroupDto } from '@/types/dtos/educator/student-group.dto';
import { DisciplineDto } from '@/types/dtos/educator/discipline.dto';
import { StudentDisciplineEnrolmentDto } from '@/types/dtos/educator/student-discipline-enrolment.dto';

type CreatePageProps = PageProps<{
    intendedInstitution: InstitutionDto | null;
    intendedStudentGroup: StudentGroupDto | null;
    intendedDiscipline: DisciplineDto | null;
    intendedStudentEnrolment: StudentDisciplineEnrolmentDto | null;
}>;

interface GradeCreationFormData {
    studentDisciplineEnrolmentKey: string;
    studentGroupKey: string;
    disciplineKey: string;
    awardedPoints: number;
    maximumPoints: number;
    notes: string;
    awardedAt: string;
}

export default function Create({
    auth,
    intendedInstitution,
    intendedStudentGroup,
    intendedDiscipline,
    intendedStudentEnrolment,
}: CreatePageProps) {
    const [selectedInstitution, setSelectedInstitution] =
        useState<InstitutionDto | null>(intendedInstitution);
    const [selectedStudentGroup, setSelectedStudentGroup] =
        useState<StudentGroupDto | null>(intendedStudentGroup);
    const [selectedDiscipline, setSelectedDiscipline] =
        useState<DisciplineDto | null>(intendedDiscipline);
    const [
        selectedStudentDisciplineEnrolment,
        setSelectedStudentDisciplineEnrolment,
    ] = useState<StudentDisciplineEnrolmentDto | null>(
        intendedStudentEnrolment
    );

    const initialRender = useRef({
        institution: true,
        discipline: true,
        studentGroup: true,
    });

    const { data, setData, errors, post, processing } =
        useForm<GradeCreationFormData>({
            studentDisciplineEnrolmentKey:
                selectedStudentDisciplineEnrolment?.key ?? '',
            studentGroupKey: selectedStudentGroup?.id ?? '',
            disciplineKey: selectedDiscipline?.id ?? '',
            awardedPoints: NaN,
            maximumPoints: NaN,
            notes: '',
            awardedAt: `${new Date().getFullYear().toString()}-${('0' + (new Date().getMonth() + 1).toString()).slice(-2)}-${('0' + new Date().getDate().toString()).slice(-2)}`,
        });

    useEffect(() => {
        if (initialRender.current.institution) {
            initialRender.current.institution = false;
            return;
        }

        setSelectedStudentGroup(null);
        setSelectedDiscipline(null);
        setSelectedStudentDisciplineEnrolment(null);
    }, [selectedInstitution]);

    useEffect(() => {
        if (initialRender.current.studentGroup) {
            initialRender.current.studentGroup = false;
            return;
        }

        setData(formData => ({
            ...formData,
            studentGroupKey: selectedStudentGroup?.id ?? '',
            disciplineKey: '',
            studentDisciplineEnrolmentKey: '',
        }));

        setSelectedDiscipline(null);
        setSelectedStudentDisciplineEnrolment(null);
    }, [selectedStudentGroup]);

    useEffect(() => {
        if (initialRender.current.discipline) {
            initialRender.current.discipline = false;
            return;
        }

        setData(formData => ({
            ...formData,
            disciplineKey: selectedDiscipline?.id ?? '',
            studentDisciplineEnrolmentKey: '',
        }));

        setSelectedStudentDisciplineEnrolment(null);
    }, [selectedDiscipline]);

    useEffect(() => {
        setData(formData => ({
            ...formData,
            studentDisciplineEnrolmentKey:
                selectedStudentDisciplineEnrolment?.key ?? '',
        }));
    }, [selectedStudentDisciplineEnrolment]);

    const submit: FormEventHandler = event => {
        post(route('educator.grades.store'));
        event.preventDefault();
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Create" />

            <form onSubmit={submit}>
                <div className="space-y-10 divide-y divide-gray-900/10">
                    <div className="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                        <div className="px-4 sm:px-0 relative">
                            <div className="sm:sticky sm:top-20">
                                <h2 className="text-base font-semibold leading-7 text-gray-900">
                                    Student
                                </h2>
                                <p className="mt-1 text-sm leading-6 text-gray-600">
                                    Select the student you are awarding a new
                                    grade to and the discipline you are awarding
                                    the grade in.
                                </p>
                            </div>
                        </div>

                        <NewGradeStudentForm
                            className="md:col-span-2"
                            selectedInstitution={selectedInstitution}
                            setSelectedInstitution={setSelectedInstitution}
                            selectedStudentGroup={selectedStudentGroup}
                            setSelectedStudentGroup={setSelectedStudentGroup}
                            selectedDiscipline={selectedDiscipline}
                            setSelectedDiscipline={setSelectedDiscipline}
                            selectedStudentDisciplineEnrolment={
                                selectedStudentDisciplineEnrolment
                            }
                            setSelectedStudentDisciplineEnrolment={
                                setSelectedStudentDisciplineEnrolment
                            }
                            errors={errors}
                            disabled={processing}
                        />
                    </div>

                    <div className="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
                        <div className="px-4 sm:px-0 relative">
                            <div className="sm:sticky sm:top-20">
                                <h2 className="text-base font-semibold leading-7 text-gray-900">
                                    Details
                                </h2>
                                <p className="mt-1 text-sm leading-6 text-gray-600">
                                    Information about the grade you are awarding
                                    the student.
                                </p>
                            </div>
                        </div>

                        <NewGradeDetailsForm
                            className="md:col-span-2"
                            data={data}
                            onChange={setData}
                            errors={errors}
                        />
                    </div>
                </div>
                <div className="sticky bottom-0 bg-white border-t rounded-lg shadow px-4 flex items-center justify-end gap-x-6 py-4 sm:px-8 mt-10">
                    <PrimaryButton type="submit" disabled={processing}>
                        {processing ? 'Saving grade' : 'Save grade'}
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticatedLayout>
    );
}
