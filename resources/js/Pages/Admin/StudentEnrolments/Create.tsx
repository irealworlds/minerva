import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import BulletsAndTextSteps from '@/Components/Steps/BulletsAndTextSteps';
import { createContext, useEffect, useState } from 'react';
import NewEnrolmentStudentProfileForm from '@/Pages/Admin/StudentEnrolments/Partials/Create/NewEnrolmentStudentProfileForm';
import { StudentRegistrationDto } from '@/types/dtos/student-registration.dto';
import NewEnrolmentInstitutionForm from '@/Pages/Admin/StudentEnrolments/Partials/Create/NewEnrolmentInstitutionForm';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import NewEnrolmentDisciplinesForm from '@/Pages/Admin/StudentEnrolments/Partials/Create/NewEnrolmentDisciplinesForm';
import NewEnrolmentPreview from '@/Pages/Admin/StudentEnrolments/Partials/Create/NewEnrolmentPreview';

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
export interface IdentityFormData {
    idNumber: string;
    namePrefix: string;
    firstName: string;
    middleNames: string[];
    lastName: string;
    nameSuffix: string;
    email: string;
}

export interface SelectableEnrolmentDiscipline {
    id: string;
    disciplineId: string;
    disciplineName: string;
    educatorId: string;
    educatorName: string;
}

interface StudentEnrolmentCreationData {
    studentKey: string | null;
    newIdentity: IdentityFormData | null;

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
    const { data, setData, processing, post } =
        useForm<StudentEnrolmentCreationData>({
            studentKey: '',
            newIdentity: null,
            studentGroupKey: '',
            disciplines: [],
        });
    const [selectedStudentProfile, setSelectedStudentProfile] =
        useState<StudentRegistrationDto | null>(null);
    const [selectedInstitution, setSelectedInstitution] =
        useState<InstitutionViewModel | null>(intendedInstitution ?? null);
    const [selectedStudentGroup, setSelectedStudentGroup] =
        useState<StudentGroupViewModel | null>(null);
    const [selectedDisciplines, setSelectedDisciplines] = useState<
        SelectableEnrolmentDiscipline[]
    >([]);

    useEffect(() => {
        if (selectedStudentProfile) {
            setData(previousData => ({
                ...previousData,
                studentKey: selectedStudentProfile.id,
            }));
        } else {
            setData(previousData => ({
                ...previousData,
                studentKey: null,
            }));
        }
    }, [selectedStudentProfile]);

    useEffect(() => {
        setData(previousData => ({
            ...previousData,
            studentGroupKey: selectedStudentGroup?.id ?? '',
        }));
    }, [selectedStudentGroup]);

    useEffect(() => {
        setData(previousData => ({
            ...previousData,
            disciplines: selectedDisciplines.map(discipline => ({
                disciplineKey: discipline.disciplineId,
                educatorKey: discipline.educatorId,
            })),
        }));
    }, [selectedDisciplines]);

    function submit() {
        post(route('admin.studentGroupEnrolments.store'));
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
                                selectedStudentProfile={selectedStudentProfile}
                                newIdentityData={data.newIdentity}
                                onChange={(
                                    studentRegistration,
                                    newIdentityData
                                ) => {
                                    setSelectedStudentProfile(
                                        studentRegistration
                                    );
                                    setData(previousData => ({
                                        ...previousData,
                                        newIdentity: newIdentityData,
                                    }));
                                }}
                                onAdvance={() => {
                                    setActiveStep(2);
                                }}
                            />
                        )}

                        {/* Institution form */}
                        {activeStep === 2 && (
                            <NewEnrolmentInstitutionForm
                                disabled={processing}
                                selectedStudentGroup={selectedStudentGroup}
                                setSelectedStudentGroup={
                                    setSelectedStudentGroup
                                }
                                selectedInstitution={selectedInstitution}
                                setSelectedInstitution={setSelectedInstitution}
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
                                studentGroup={selectedStudentGroup}
                                value={selectedDisciplines}
                                onChange={disciplines => {
                                    setSelectedDisciplines(disciplines);
                                }}
                                disabled={processing}
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
                                    newStudent: data.newIdentity !== null,
                                    studentName: selectedStudentProfile
                                        ? selectedStudentProfile.name
                                        : data.newIdentity
                                          ? `${data.newIdentity.firstName} ${data.newIdentity.lastName}`
                                          : null,
                                    studentPictureUri: selectedStudentProfile
                                        ? selectedStudentProfile.pictureUri
                                        : 'https://ui-avatars.com/api/?name=' +
                                          encodeURIComponent(
                                              `${data.newIdentity?.firstName ?? ''} ${data.newIdentity?.lastName ?? ''}`
                                          ) +
                                          '&background=random&size=128',
                                    selectedInstitution,
                                    selectedStudentGroup,
                                    selectedDisciplines,
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
