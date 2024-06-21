import { Link, useForm } from '@inertiajs/react';
import React, { FormEventHandler, useEffect, useRef, useState } from 'react';
import { clearFileInput } from '@/utils/clear-file-input.function';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import { BuildingLibraryIcon } from '@heroicons/react/24/outline';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import DangerButton from '@/Components/Buttons/DangerButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import InstitutionPicture from '@/Components/Institutions/InstitutionPicture';

interface UpdatedInstitutionPublicDetailsFormProps {
    institution: InstitutionViewModel;
    parent: {
        id: string;
        name: string;
        pictureUri: string | null;
        ancestors: {
            id: string;
            name: string;
        }[];
    } | null;
}

export default function UpdateInstitutionPublicDetailsForm({
    institution,
    parent,
}: UpdatedInstitutionPublicDetailsFormProps) {
    const { setData, data, processing, errors, post, reset, isDirty } =
        useForm<{
            _method: 'PATCH';
            name: string;
            website: string;
            picture: File | null | undefined;
        }>({
            _method: 'PATCH',
            name: institution.name,
            website: institution.website ?? '',
            picture: undefined,
        });

    const pictureInput = useRef<HTMLInputElement>(null);
    const [pictureInputPreview, setPictureInputPreview] = useState<
        string | null
    >(null);

    const updatePictureInputValue = (newValue: File | null | undefined) => {
        // Update the preview data URL
        setPictureInputPreview(null);
        if (newValue) {
            const reader = new FileReader();
            reader.onload = function () {
                if (typeof reader.result === 'string') {
                    setPictureInputPreview(reader.result);
                }
            };
            reader.readAsDataURL(newValue);
        } else if (newValue === undefined) {
            setPictureInputPreview(institution.pictureUri);
        }

        if (!newValue && pictureInput.current?.files?.length) {
            clearFileInput(pictureInput.current);
        }

        // Update the form input
        setData('picture', newValue);
    };

    const submit: FormEventHandler = e => {
        e.preventDefault();
        post(
            route('admin.institutions.update.public', {
                institution: institution.id,
            }),
            {
                preserveScroll: true,
                forceFormData: data.picture instanceof File,
            }
        );
    };

    useEffect(() => {
        setData('name', institution.name);
        setData('website', institution.website ?? '');
        updatePictureInputValue(undefined);
    }, [institution]);

    return (
        <form onSubmit={submit} encType="multipart/form-data">
            <div className="bg-white p-6 rounded-lg shadow">
                <div className="border-b border-gray-900/10 pb-12">
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Public profile
                    </h2>
                    <p className="mt-1 text-sm leading-6 text-gray-600">
                        Information about this institution that will be publicly
                        accessible to all users.
                    </p>

                    <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        {/* Name */}
                        <div className="sm:col-span-4">
                            <InputLabel htmlFor="name" value="Name" />

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
                        </div>

                        {/* Website */}
                        <div className="sm:col-span-4">
                            <InputLabel htmlFor="website" value="Website" />

                            <TextInput
                                id="website"
                                type="text"
                                name="website"
                                value={data.website}
                                className="mt-1 block w-full"
                                placeholder="A link to the institution's website"
                                onChange={e => {
                                    setData('website', e.target.value);
                                }}
                            />

                            <InputError
                                message={errors.website}
                                className="mt-2"
                            />
                        </div>

                        {/* Picture */}
                        <div className="col-span-full">
                            <label
                                htmlFor="photo"
                                className="block text-sm font-medium leading-6 text-gray-900">
                                Picture
                            </label>
                            <input
                                id="picture"
                                type="file"
                                accept="image/*"
                                className="hidden"
                                ref={pictureInput}
                                onChange={e => {
                                    updatePictureInputValue(
                                        e.target.files?.item(0) ?? null
                                    );
                                }}
                            />

                            <div className="mt-2 flex flex-wrap items-center gap-y-6 gap-x-3 mt-4">
                                {pictureInputPreview ? (
                                    <img
                                        src={pictureInputPreview}
                                        className="size-12 rounded-full border"
                                        alt="Upload preview"
                                    />
                                ) : (
                                    <div
                                        className="size-12 bg-gray-800 flex items-center justify-center rounded-full text-white"
                                        aria-hidden="true">
                                        <BuildingLibraryIcon className="size-8" />
                                    </div>
                                )}
                                {data.picture !== undefined && (
                                    <SecondaryButton
                                        type="button"
                                        onClick={() => {
                                            updatePictureInputValue(undefined);
                                        }}>
                                        Discard changes
                                    </SecondaryButton>
                                )}
                                {data.picture !== null &&
                                    institution.pictureUri !== null && (
                                        <DangerButton
                                            type="button"
                                            onClick={() => {
                                                updatePictureInputValue(null);
                                            }}>
                                            Clear
                                        </DangerButton>
                                    )}
                                <PrimaryButton
                                    type="button"
                                    onClick={() =>
                                        pictureInput.current?.click()
                                    }>
                                    Change
                                </PrimaryButton>
                            </div>
                        </div>

                        {/* Parent */}
                        {parent && (
                            <div className="col-span-full">
                                <p className="block text-sm font-medium leading-6 text-gray-900">
                                    Parent institution
                                </p>

                                <div className="mt-2">
                                    <nav className="truncate text-gray-400">
                                        <ol className="flex flex-wrap items-center gap-x-1">
                                            {parent.ancestors.map(ancestor => (
                                                <li
                                                    key={ancestor.id}
                                                    className="flex items-center">
                                                    <Link
                                                        href={route(
                                                            'admin.institutions.show.general',
                                                            {
                                                                institution:
                                                                    ancestor.id,
                                                            }
                                                        )}
                                                        className="mr-1 text-xs font-medium hover:text-gray-500">
                                                        {ancestor.name}
                                                    </Link>
                                                    <svg
                                                        className="size-3 flex-shrink-0"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                        aria-hidden="true">
                                                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                                    </svg>
                                                </li>
                                            ))}
                                        </ol>
                                    </nav>
                                    <Link
                                        href={route(
                                            'admin.institutions.show.general',
                                            {
                                                institution: parent.id,
                                            }
                                        )}
                                        className="mt-2 text-sm leading-6 text-indigo-600 hover:text-indigo-500 flex items-center gap-2">
                                        <InstitutionPicture
                                            uri={parent.pictureUri}
                                            className="size-8"
                                        />
                                        {parent.name}
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                <div className="mt-6 flex items-center justify-end gap-x-6">
                    {isDirty && (
                        <SecondaryButton
                            type="button"
                            onClick={() => {
                                reset();
                            }}
                            disabled={processing}>
                            Reset
                        </SecondaryButton>
                    )}
                    <PrimaryButton type="submit" disabled={processing}>
                        {processing ? 'Saving' : 'Save changes'}
                    </PrimaryButton>
                </div>
            </div>
        </form>
    );
}
