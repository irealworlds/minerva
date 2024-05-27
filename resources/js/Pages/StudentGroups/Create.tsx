import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import BulletsAndTextSteps from '@/Components/Steps/BulletsAndTextSteps';
import { useState } from 'react';
import NewStudentGroupParentForm from '@/Pages/StudentGroups/Partials/Create/NewStudentGroupParentForm';
import NewStudentGroupDetailsForm from '@/Pages/StudentGroups/Partials/Create/NewStudentGroupDetailsForm';
import NewStudentGroupPreview from '@/Pages/StudentGroups/Partials/Create/NewStudentGroupPreview';
import { InstitutionViewModel } from '@/types/ViewModels/institution.view-model';
import { StudentGroupViewModel } from '@/types/ViewModels/student-group.view-model';

const steps = [
  {
    id: 1,
    name: 'Parent',
  },
  {
    id: 2,
    name: 'Details',
  },
  {
    id: 3,
    name: 'Preview',
  },
];

export interface GroupCreationFormData {
  parentType: 'institution' | 'studentGroup' | null;
  parent: InstitutionViewModel | StudentGroupViewModel | null;
  name: string;
}
export interface GroupCreationRequestData {
  parentType: 'institution' | 'studentGroup';
  parentId: string;
  name: string;
}

export default function Create({
  auth,
  initialParentType,
  initialParent,
}: PageProps<{
  initialParentType: 'institution' | 'studentGroup' | null;
  initialParent: InstitutionViewModel | StudentGroupViewModel | null;
}>) {
  const [activeStep, setActiveStep] = useState(steps[0].id);
  const { data, setData, errors, processing, post, transform } =
    useForm<GroupCreationFormData>({
      // Parent
      parentType: initialParentType,
      parent: initialParent,

      // Details
      name: '',
    });

  transform(
    formData =>
      ({
        parentType: formData.parentType,
        parentId: formData.parent?.id ?? '',
        name: formData.name,
      }) as GroupCreationRequestData as unknown as GroupCreationFormData
  );

  function createGroup(): void {
    post(route('student_groups.store'));
  }

  return (
    <AuthenticatedLayout user={auth.user}>
      <Head title="Create student group" />

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
          {/* Parent form */}
          {activeStep === 1 && (
            <NewStudentGroupParentForm
              data={data}
              setData={setData}
              errors={errors}
              disabled={processing}
              onAdvance={() => {
                setActiveStep(2);
              }}
            />
          )}

          {/* Details form */}
          {activeStep === 2 && (
            <NewStudentGroupDetailsForm
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
            <NewStudentGroupPreview
              disabled={processing}
              data={data}
              onPreviousRequested={() => {
                setActiveStep(2);
              }}
              onAdvance={() => {
                createGroup();
              }}
            />
          )}
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
