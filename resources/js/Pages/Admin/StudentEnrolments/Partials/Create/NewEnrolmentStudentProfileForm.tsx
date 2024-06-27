import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { ArrowRightIcon } from '@heroicons/react/20/solid';
import React, { useMemo } from 'react';
import { StudentRegistrationDto } from '@/types/dtos/student-registration.dto';
import StudentRegistrationSelector from '@/Pages/Admin/StudentEnrolments/Components/StudentRegistrationSelector';
import NewEnrolmentIdentityForm from '@/Pages/Admin/StudentEnrolments/Partials/Create/NewEnrolmentIdentityForm';
import { IdentityFormData } from '@/Pages/Admin/StudentEnrolments/Create';

interface NewEnrolmentStudentProfileFormProps {
    disabled?: boolean;

    newIdentityData: IdentityFormData | null;
    selectedStudentProfile: StudentRegistrationDto | null;
    onChange: (
        studentRegistration: StudentRegistrationDto | null,
        newIdentityData: IdentityFormData | null
    ) => void;

    onAdvance: () => void;
    onPreviousRequested?: () => void;
}

export default function NewEnrolmentStudentProfileForm({
    disabled,
    newIdentityData,
    selectedStudentProfile,
    onChange,
    onAdvance,
    onPreviousRequested,
}: NewEnrolmentStudentProfileFormProps) {
    const useNewIdentity = useMemo(() => {
        return newIdentityData !== null;
    }, [newIdentityData]);

    function setUseNewIdentity(value: boolean) {
        if (value) {
            onChange(null, {
                idNumber: '',
                namePrefix: '',
                firstName: '',
                middleNames: [],
                lastName: '',
                nameSuffix: '',
                email: '',
            });
        } else {
            onChange(null, null);
        }
    }

    function setSelectedStudentProfile(profile: StudentRegistrationDto | null) {
        if (!useNewIdentity) {
            onChange(profile, null);
        }
    }

    function setNewIdentityData(data: IdentityFormData | null) {
        if (useNewIdentity) {
            onChange(null, data);
        }
    }

    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Student registration
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Select the student you want to enrol into an institution
                        or register a new student into the system.
                    </p>

                    <div className="mt-10">
                        <fieldset>
                            <div className="space-y-5">
                                {/* Use existing student */}
                                <div className="relative flex items-start">
                                    <div className="flex h-6 items-center">
                                        <input
                                            id="use-existing-student"
                                            name="plan"
                                            type="radio"
                                            checked={!useNewIdentity}
                                            disabled={disabled}
                                            onChange={event => {
                                                if (event.target.checked) {
                                                    setUseNewIdentity(false);
                                                }
                                            }}
                                            className="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                        />
                                    </div>
                                    <div className="ml-3 text-sm leading-6 grow">
                                        <label
                                            htmlFor="use-existing-student"
                                            className="font-medium text-gray-900">
                                            Use existing student registration
                                        </label>
                                        <p className="text-gray-500">
                                            which is already in the system
                                        </p>

                                        {!useNewIdentity && (
                                            <div className="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 border-t border-gray-200 mt-3 pt-3 px-6">
                                                <StudentRegistrationSelector
                                                    className="sm:col-span-4"
                                                    value={
                                                        selectedStudentProfile
                                                    }
                                                    onChange={newValue => {
                                                        setSelectedStudentProfile(
                                                            newValue
                                                        );
                                                    }}
                                                />
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Create new identity */}
                                <div className="relative flex items-start">
                                    <div className="flex h-6 items-center">
                                        <input
                                            id="create-new-identity"
                                            name="plan"
                                            type="radio"
                                            checked={useNewIdentity}
                                            disabled={disabled}
                                            onChange={event => {
                                                if (event.target.checked) {
                                                    setUseNewIdentity(true);
                                                }
                                            }}
                                            className="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                        />
                                    </div>
                                    <div className="ml-3 text-sm leading-6 grow">
                                        <label
                                            htmlFor="create-new-identity"
                                            className="font-medium text-gray-900">
                                            Use a new student registration
                                        </label>
                                        <p className="text-gray-500">
                                            which will be registered into the
                                            system
                                        </p>

                                        {useNewIdentity && newIdentityData && (
                                            <NewEnrolmentIdentityForm
                                                className="border-t border-gray-200 mt-3 pt-3 px-6"
                                                data={newIdentityData}
                                                onChange={setNewIdentityData}
                                            />
                                        )}
                                    </div>
                                </div>
                            </div>
                        </fieldset>
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
