import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import BulletsAndTextSteps from '@/Components/Steps/BulletsAndTextSteps';
import { useState } from 'react';
import NewDisciplineDetailsForm from '@/Pages/Disciplines/Partials/Create/NewDisciplineDetailsForm';
import NewDisciplineAssociations from '@/Pages/Disciplines/Partials/Create/NewDisciplineAssociations';
import NewDisciplinePreview from '@/Pages/Disciplines/Partials/Create/NewDisciplinePreview';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

const steps = [
    {
        id: 1,
        name: 'Details',
    },
    {
        id: 2,
        name: 'Associations',
    },
    {
        id: 3,
        name: 'Preview',
    },
];

export interface DisciplineCreationFormData {
    name: string;
    abbreviation: string;
    associatedInstitutions: InstitutionViewModel[];
}

export interface DisciplineCreationRequestData {
    name: string;
    abbreviation: string | null;
    associatedInstitutionKeys: string[];
}

export default function Create({
    auth,
    initialInstitutions,
}: PageProps<{ initialInstitutions: InstitutionViewModel[] | null }>) {
    const [activeStep, setActiveStep] = useState(steps[0].id);
    const { data, setData, processing, errors, post, transform } =
        useForm<DisciplineCreationFormData>({
            name: '',
            abbreviation: '',
            associatedInstitutions: initialInstitutions ?? [],
        });

    transform(
        formData =>
            ({
                name: formData.name,
                abbreviation: formData.abbreviation.length
                    ? formData.abbreviation
                    : null,
                associatedInstitutionKeys: formData.associatedInstitutions.map(
                    i => i.id
                ),
            }) as DisciplineCreationRequestData as unknown as DisciplineCreationFormData
    );

    function submit(): void {
        if (processing) {
            throw new Error("Can't submit while processing.");
        }

        post(route('disciplines.store'));
    }
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Create discipline" />

            <div className="flex flex-col lg:flex-row gap-12">
                {/* Steps */}
                <div>
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
                    {/* Details form */}
                    {activeStep === 1 && (
                        <NewDisciplineDetailsForm
                            disabled={processing}
                            data={data}
                            setData={setData}
                            errors={errors}
                            onAdvance={() => {
                                setActiveStep(2);
                            }}
                        />
                    )}
                    {/* Associations */}
                    {activeStep === 2 && (
                        <NewDisciplineAssociations
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
                    {/* Preview */}
                    {activeStep === 3 && (
                        <NewDisciplinePreview
                            disabled={processing}
                            data={data}
                            setData={setData}
                            errors={errors}
                            onPreviousRequested={() => {
                                setActiveStep(2);
                            }}
                            onAdvance={() => {
                                submit();
                            }}
                        />
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
