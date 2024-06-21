import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { ArrowRightIcon } from '@heroicons/react/20/solid';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import React from 'react';
import { GroupCreationFormData } from '@/Pages/Admin/StudentGroups/Create';

interface NewStudentGroupDetailsFormProps {
    data: GroupCreationFormData;
    setData: ((data: GroupCreationFormData) => void) &
        ((
            data: (previousData: GroupCreationFormData) => GroupCreationFormData
        ) => void) &
        (<K extends keyof GroupCreationFormData>(
            key: K,
            value: GroupCreationFormData[K]
        ) => void);
    errors: Partial<Record<'parentType' | 'parentId' | 'name', string>>;
    onAdvance: () => void;
    onPreviousRequested: () => void;
    disabled?: boolean;
}

export default function NewStudentGroupDetailsForm({
    onAdvance,
    data,
    setData,
    errors,
    onPreviousRequested,
    disabled,
}: NewStudentGroupDetailsFormProps) {
    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Details
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Information about student group that is being created
                    </p>

                    <div className="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
                        {/* Name */}
                        <div className="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                            <InputLabel htmlFor="name" value="Name" />
                            <div className="mt-2 sm:col-span-2 sm:mt-0">
                                <TextInput
                                    id="name"
                                    type="text"
                                    name="name"
                                    value={data.name}
                                    disabled={disabled}
                                    className="mt-1 block w-full sm:max-w-md"
                                    placeholder="This institution's name"
                                    onChange={e => {
                                        setData('name', e.target.value);
                                    }}
                                />

                                <InputError
                                    message={errors.name}
                                    className="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="mt-6 flex items-center justify-end gap-x-3">
                <SecondaryButton
                    disabled={disabled}
                    type="button"
                    onClick={() => {
                        onPreviousRequested();
                    }}>
                    Back
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
