import React from 'react';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { ArrowRightIcon } from '@heroicons/react/20/solid';
import ParentInstitutionSelector from '@/Pages/Admin/StudentEnrolments/Components/ParentInstitutionSelector';
import ParentGroupSelector from '@/Pages/Admin/StudentEnrolments/Components/StudentGroupSelector';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

interface NewEnrolmentInstitutionFormProps {
    disabled?: boolean;

    selectedInstitution: InstitutionViewModel | null;
    setSelectedInstitution: (value: InstitutionViewModel | null) => void;
    selectedStudentGroup: StudentGroupViewModel | null;
    setSelectedStudentGroup: (value: StudentGroupViewModel | null) => void;

    onAdvance: () => void;
    onPreviousRequested?: () => void;
}

export default function NewEnrolmentInstitutionForm({
    disabled,
    selectedInstitution,
    setSelectedInstitution,
    selectedStudentGroup,
    setSelectedStudentGroup,
    onAdvance,
    onPreviousRequested,
}: NewEnrolmentInstitutionFormProps) {
    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Student Group
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Select the institution and the student group this
                        student will be enroled in.
                    </p>

                    <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        {/* Select institution */}
                        <ParentInstitutionSelector
                            className="sm:col-span-4"
                            value={selectedInstitution}
                            onChange={setSelectedInstitution}
                        />

                        {selectedInstitution && (
                            <ParentGroupSelector
                                className="sm:col-span-4"
                                value={selectedStudentGroup}
                                onChange={setSelectedStudentGroup}
                            />
                        )}
                    </div>
                </div>
            </div>

            <div className="mt-6 flex items-center justify-end gap-x-3">
                {onPreviousRequested && (
                    <SecondaryButton
                        disabled={disabled}
                        type="button"
                        onClick={() => {
                            onPreviousRequested();
                        }}>
                        Back
                    </SecondaryButton>
                )}
                <PrimaryButton
                    disabled={disabled}
                    type="submit"
                    onClick={() => {
                        onAdvance();
                    }}>
                    Advance
                    <ArrowRightIcon className="size-4 ml-2" />
                </PrimaryButton>
            </div>
        </>
    );
}
