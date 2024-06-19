import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { ArrowRightIcon } from '@heroicons/react/20/solid';
import React from 'react';
import StudentRegistrationSelector from '@/Pages/StudentEnrolments/Components/StudentRegistrationSelector';
import { StudentRegistrationDto } from '@/types/dtos/student-registration.dto';

interface StudentProfileForm {
    studentProfile: StudentRegistrationDto | null;
}
interface NewEnrolmentStudentProfileFormProps {
    data: StudentProfileForm;
    setData: <K extends keyof StudentProfileForm>(
        key: K,
        value: StudentProfileForm[K]
    ) => void;
    errors: Partial<Record<keyof StudentProfileForm, string>>;
    onAdvance: () => void;
    onPreviousRequested?: () => void;
    disabled?: boolean;
}

export default function NewEnrolmentStudentProfileForm({
    onAdvance,
    data,
    setData,
    onPreviousRequested,
    disabled,
}: NewEnrolmentStudentProfileFormProps) {
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

                    <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <StudentRegistrationSelector
                            className="sm:col-span-4"
                            value={data.studentProfile}
                            onChange={newValue => {
                                setData('studentProfile', newValue);
                            }}
                        />
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
