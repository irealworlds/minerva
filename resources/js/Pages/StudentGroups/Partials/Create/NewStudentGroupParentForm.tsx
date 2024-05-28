import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { ArrowRightIcon } from '@heroicons/react/20/solid';
import { GroupCreationFormData } from '@/Pages/StudentGroups/Create';
import ButtonRadioInput from '@/Components/Forms/Controls/ButtonRadioInput';
import ButtonRadioInputOption from '@/Components/Forms/Controls/ButtonRadioInputOption';
import InputLabel from '@/Components/Forms/InputLabel';
import ParentInstitutionSelector from '@/Pages/StudentGroups/Components/ParentInstitutionSelector';
import ParentGroupSelector from '@/Pages/StudentGroups/Components/ParentGroupSelector';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import InputError from '@/Components/Forms/InputError';
import React from 'react';

export default function NewStudentGroupParentForm({
    onAdvance,
    data,
    setData,
    disabled,
    errors,
}: {
    data: GroupCreationFormData;
    disabled: boolean;
    setData: ((data: GroupCreationFormData) => void) &
        ((
            data: (previousData: GroupCreationFormData) => GroupCreationFormData
        ) => void) &
        (<K extends keyof GroupCreationFormData>(
            key: K,
            value: GroupCreationFormData[K]
        ) => void);
    onAdvance: () => void;
    errors: Partial<Record<'parentType' | 'parent' | 'name', string>>;
}) {
    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Parent
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Every student group must be subordinated to either an
                        institution or a student group and be integrated in its
                        structure.
                    </p>

                    <div className="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
                        {/*  Parent type */}
                        <div className="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                            <InputLabel value="Parent type" />
                            <div className="mt-2 sm:col-span-2 sm:mt-0">
                                <ButtonRadioInput<typeof data.parentType>
                                    value={data.parentType}
                                    disabled={disabled}
                                    onChange={value => {
                                        setData(previous => ({
                                            ...previous,
                                            parent: null,
                                            parentType: value,
                                        }));
                                    }}>
                                    <ButtonRadioInputOption value="institution">
                                        Institution
                                    </ButtonRadioInputOption>
                                    <ButtonRadioInputOption value="studentGroup">
                                        Student group
                                    </ButtonRadioInputOption>
                                </ButtonRadioInput>
                                <InputError
                                    message={errors.parentType}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        {/*  Parent type */}
                        {!!data.parentType && (
                            <div className="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                                <InputLabel>
                                    Parent{' '}
                                    {data.parentType === 'institution'
                                        ? 'institution'
                                        : 'student group'}
                                </InputLabel>
                                <div className="mt-2 sm:col-span-2 sm:mt-0">
                                    {data.parentType === 'institution' && (
                                        <ParentInstitutionSelector
                                            disabled={disabled}
                                            value={
                                                data.parent as InstitutionViewModel
                                            }
                                            onChange={value => {
                                                setData('parent', value);
                                            }}
                                        />
                                    )}
                                    {data.parentType === 'studentGroup' && (
                                        <ParentGroupSelector
                                            disabled={disabled}
                                            value={
                                                data.parent as StudentGroupViewModel
                                            }
                                            onChange={value => {
                                                setData('parent', value);
                                            }}
                                        />
                                    )}
                                    <InputError
                                        message={errors.parent}
                                        className="mt-2"
                                    />
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            <div className="mt-6 flex items-center justify-end gap-x-3">
                <SecondaryButton type="button" disabled={disabled}>
                    Cancel
                </SecondaryButton>
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
