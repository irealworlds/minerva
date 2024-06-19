import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import BulletsAndTextSteps from '@/Components/Steps/BulletsAndTextSteps';
import { createContext, useState } from 'react';
import NewEnrolmentStudentProfileForm from '@/Pages/StudentEnrolments/Partials/NewEnrolmentStudentProfileForm';
import { StudentRegistrationDto } from '@/types/dtos/student-registration.dto';
import NewEnrolmentInstitutionForm from '@/Pages/StudentEnrolments/Partials/NewEnrolmentInstitutionForm';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import NewEnrolmentDisciplinesForm, {
    SelectableEnrolmentDiscipline,
} from '@/Pages/StudentEnrolments/Partials/NewEnrolmentDisciplinesForm';
import NewEnrolmentPreview from '@/Pages/StudentEnrolments/Partials/NewEnrolmentPreview';

const steps = [
    {
        id: 1,
        name: 'Student profile',
    },
    {
        id: 2,
        name: 'Student group',
    },
    {
        id: 3,
        name: 'Disciplines',
    },
    {
        id: 4,
        name: 'Preview',
    },
];

export interface NewStudentEnrolmentFormData {
    studentProfile: StudentRegistrationDto | null;
    studentGroup: StudentGroupViewModel | null;
    disciplines: SelectableEnrolmentDiscipline[];
}

interface NewStudentEnrolmentCreationData {
    studentKey: string;
    studentGroupKey: string;
    disciplines: {
        disciplineKey: string;
        educatorKey: string;
    }[];
}

type CreatePageProps = PageProps<{
    intendedInstitution: InstitutionViewModel | null;
}>;

export const StudentEnrolmentCreationContext = createContext<{
    selectedInstitution: InstitutionViewModel | null;
    setSelectedInstitution: (institution: InstitutionViewModel | null) => void;
}>({
    selectedInstitution: null,
    setSelectedInstitution: () => {
        // Do nothing
    },
});

export default function Create({ auth, intendedInstitution }: CreatePageProps) {
    const [activeStep, setActiveStep] = useState(steps[0].id);
    const { data, setData, processing, errors, post, transform } =
        useForm<NewStudentEnrolmentFormData>({
            studentProfile: null,
            studentGroup: null,
            disciplines: [],
        });
    const [selectedInstitution, setSelectedInstitution] =
        useState<InstitutionViewModel | null>(intendedInstitution ?? null);

    transform(data => {
        if (!data.studentProfile) {
            throw new Error('Student profile is required');
        }

        if (!data.studentGroup) {
            throw new Error('Student group is required');
        }

        return {
            studentKey: data.studentProfile.id,
            studentGroupKey: data.studentGroup.id,
            disciplines: data.disciplines.map(d => ({
                disciplineKey: d.disciplineId,
                educatorKey: d.educatorId,
            })),
        } as NewStudentEnrolmentCreationData as unknown as NewStudentEnrolmentFormData;
    });

    function submit() {
        post(route('student_enrolments.store'));
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Enrol student" />

            <div className="flex flex-col lg:flex-row gap-12">
                {/* Steps */}
                <div className="shrink-0">
                    <BulletsAndTextSteps
                        disabled={processing}
                        steps={steps}
                        activeStepId={activeStep}
                        className="sticky top-10 px-4 py-12 sm:px-6 lg:px-8"
                        onActiveStepChange={newStepId => {
                            setActiveStep(newStepId);
                        }}
                    />
                </div>

                {/* Content*/}
                <div className="grow bg-white p-10 shadow rounded-lg">
                    <StudentEnrolmentCreationContext.Provider
                        value={{
                            selectedInstitution,
                            setSelectedInstitution,
                        }}>
                        {/* Student form */}
                        {activeStep === 1 && (
                            <NewEnrolmentStudentProfileForm
                                disabled={processing}
                                data={data}
                                setData={setData}
                                errors={errors}
                                onAdvance={() => {
                                    setActiveStep(2);
                                }}
                            />
                        )}

                        {/* Institution form */}
                        {activeStep === 2 && (
                            <NewEnrolmentInstitutionForm
                                disabled={processing}
                                data={data}
                                setData={setData}
                                errors={errors}
                                onPreviousRequested={() => {
                                    setActiveStep(1);
                                }}
                                onAdvance={() => {
                                    setActiveStep(3);
                                }}
                            />
                        )}

                        {/* Disciplines form */}
                        {activeStep === 3 && (
                            <NewEnrolmentDisciplinesForm
                                studentGroup={data.studentGroup}
                                disabled={processing}
                                data={data}
                                setData={setData}
                                errors={errors}
                                onPreviousRequested={() => {
                                    setActiveStep(2);
                                }}
                                onAdvance={() => {
                                    setActiveStep(4);
                                }}
                            />
                        )}

                        {/* Disciplines form */}
                        {activeStep === 4 && (
                            <NewEnrolmentPreview
                                data={{
                                    ...data,
                                    institution: intendedInstitution,
                                }}
                                disabled={processing}
                                onPreviousRequested={() => {
                                    setActiveStep(3);
                                }}
                                onAdvance={() => {
                                    submit();
                                }}
                            />
                        )}
                    </StudentEnrolmentCreationContext.Provider>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
