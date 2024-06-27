import React from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';
import { IdentityFormData } from '@/Pages/Admin/StudentEnrolments/Create';

interface NewEnrolmentIdentityFormProps {
    className?: string;

    data: IdentityFormData;
    onChange: (newValue: IdentityFormData) => void;
    errors?: Partial<Record<keyof IdentityFormData, string>>;
    disabled?: boolean;
}

export default function NewEnrolmentIdentityForm({
    className,
    data,
    onChange,
    errors,
    disabled,
}: NewEnrolmentIdentityFormProps) {
    return (
        <div
            className={combineClassNames(
                className,
                'grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6'
            )}>
            {/* Id number */}
            <div className="col-span-full">
                <InputLabel htmlFor="idNumber" value="Id number" />

                <TextInput
                    id="idNumber"
                    name="idNumber"
                    value={data.idNumber}
                    className="mt-1 block w-full"
                    autoComplete="new-username"
                    disabled={disabled}
                    onChange={e => {
                        onChange({
                            ...data,
                            idNumber: e.target.value,
                        });
                    }}
                    placeholder="e.g. 1234567890123"
                />

                <InputError message={errors?.idNumber} className="mt-2" />
            </div>

            {/* Name prefix */}
            <div className="sm:col-span-2">
                <InputLabel htmlFor="namePrefix" value="Name prefix" />

                <TextInput
                    id="namePrefix"
                    name="namePrefix"
                    value={data.namePrefix}
                    className="mt-1 block w-full"
                    autoComplete="honorific-prefix"
                    disabled={disabled}
                    onChange={e => {
                        onChange({
                            ...data,
                            namePrefix: e.target.value,
                        });
                    }}
                    placeholder="e.g. Dr."
                />

                <InputError message={errors?.namePrefix} className="mt-2" />
            </div>

            {/* First name */}
            <div className="sm:col-span-4">
                <InputLabel htmlFor="firstName" value="First name" />

                <TextInput
                    id="firstName"
                    name="firstName"
                    value={data.firstName}
                    className="mt-1 block w-full"
                    autoComplete="given-name"
                    disabled={disabled}
                    onChange={e => {
                        onChange({
                            ...data,
                            firstName: e.target.value,
                        });
                    }}
                    placeholder="e.g. John"
                    required
                />

                <InputError message={errors?.firstName} className="mt-2" />
            </div>

            {/* Middle names */}
            <div className="sm:col-span-6">
                <InputLabel htmlFor="middleNames" value="Middle names" />

                <TextChipsInput
                    id="middleNames"
                    name="middleNames"
                    value={data.middleNames}
                    className="mt-1 block w-full"
                    autoComplete="additional-name"
                    disabled={disabled}
                    onChange={names => {
                        onChange({
                            ...data,
                            middleNames: names,
                        });
                    }}
                    placeholder="e.g. Adam, Alan, Carl"
                />
                <p className="text-xs text-right text-gray-500 dark:text-gray-400 mt-1">
                    Enter your middle names, separated by commas.
                </p>

                <InputError message={errors?.middleNames} className="mt-2" />
            </div>

            {/* Last name */}
            <div className="sm:col-span-4">
                <InputLabel htmlFor="lastName" value="Last name" />

                <TextInput
                    id="lastName"
                    name="lastName"
                    value={data.lastName}
                    className="mt-1 block w-full"
                    autoComplete="family-name"
                    disabled={disabled}
                    onChange={e => {
                        onChange({
                            ...data,
                            lastName: e.target.value,
                        });
                    }}
                    placeholder="e.g. Doe"
                    required
                />

                <InputError message={errors?.lastName} className="mt-2" />
            </div>

            {/* Name suffix */}
            <div className="sm:col-span-2">
                <InputLabel htmlFor="nameSuffix" value="Name suffix" />

                <TextInput
                    id="nameSuffix"
                    name="nameSuffix"
                    value={data.nameSuffix}
                    className="mt-1 block w-full"
                    autoComplete="honorific-suffix"
                    disabled={disabled}
                    onChange={e => {
                        onChange({
                            ...data,
                            nameSuffix: e.target.value,
                        });
                    }}
                    placeholder="e.g. PhD"
                />

                <InputError message={errors?.nameSuffix} className="mt-2" />
            </div>

            {/* E-mail address */}
            <div className="sm:col-span-6">
                <InputLabel htmlFor="email" value="E-mail address" />

                <TextInput
                    id="email"
                    type="email"
                    name="email"
                    value={data.email}
                    className="mt-1 block w-full"
                    autoComplete="email"
                    disabled={disabled}
                    onChange={e => {
                        onChange({
                            ...data,
                            email: e.target.value,
                        });
                    }}
                    placeholder="e.g. you@example.com"
                    required
                />

                <InputError message={errors?.email} className="mt-2" />
            </div>
        </div>
    );
}
