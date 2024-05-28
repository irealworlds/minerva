import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import React, { FormEventHandler } from 'react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import { useForm } from '@inertiajs/react';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import InputLabel from '@/Components/Forms/InputLabel';

interface StudentGroupUpdateInformationProps {
    group: StudentGroupViewModel;
    setModifyingSection: (section: 'information' | null) => void;
}

export default function StudentGroupUpdateInformation({
    group,
    setModifyingSection,
}: StudentGroupUpdateInformationProps) {
    const { data, setData, errors, processing, patch } = useForm({
        name: group.name,
    });

    const submit: FormEventHandler = e => {
        e.preventDefault();

        patch(
            route('student_groups.update', {
                group: group.id,
            })
        );
    };

    return (
        <form onSubmit={submit}>
            {/* Information */}
            <div>
                <div className="flex items-center gap-2">
                    {/* Back button */}
                    <button
                        type="button"
                        onClick={() => {
                            setModifyingSection(null);
                        }}
                        disabled={processing}
                        className="relative flex size-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span className="absolute -inset-1.5" />
                        <ArrowLeftIcon className="size-5" aria-hidden="true" />
                        <span className="sr-only">Back</span>
                    </button>

                    {/* Title */}
                    <h3 className="font-medium text-gray-900">
                        Modifying information
                    </h3>
                </div>
                <dl className="mt-2 mb-4 divide-y divide-gray-200 border-b border-t border-gray-200">
                    {/* Name */}
                    <div className="py-3 text-sm font-medium">
                        <dt className="text-gray-500">
                            <InputLabel htmlFor="name" value="Name" />
                        </dt>
                        <dd className="text-gray-900">
                            <TextInput
                                id="name"
                                type="text"
                                name="name"
                                value={data.name}
                                className="mt-1 block w-full"
                                placeholder="This institution's name"
                                onChange={e => {
                                    setData('name', e.target.value);
                                }}
                            />

                            <InputError
                                message={errors.name}
                                className="mt-2"
                            />
                        </dd>
                    </div>

                    {/* Created at */}
                    <div className="flex justify-between py-3 text-sm font-medium">
                        <dt className="text-gray-500">Created</dt>
                        <dd className="text-gray-900">
                            {new Date(group.createdAt).toLocaleDateString(
                                undefined,
                                {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric',
                                }
                            )}
                        </dd>
                    </div>

                    <div className="flex justify-between py-3 text-sm font-medium">
                        <dt className="text-gray-500">Last modified</dt>
                        <dd className="text-gray-900">
                            {new Date(group.updatedAt).toLocaleDateString(
                                undefined,
                                {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric',
                                }
                            )}
                        </dd>
                    </div>
                </dl>
            </div>

            {/* Actions */}
            <div className="flex items-center gap-3">
                <SecondaryButton
                    type="button"
                    disabled={processing}
                    onClick={() => {
                        setModifyingSection(null);
                    }}
                    className="grow justify-center">
                    Cancel
                </SecondaryButton>
                <PrimaryButton
                    type="submit"
                    disabled={processing}
                    className="grow justify-center">
                    Save changes
                </PrimaryButton>
            </div>
        </form>
    );
}
