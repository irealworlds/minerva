import InputError from '@/Components/Forms/InputError';
import InputLabel from '@/Components/Forms/InputLabel';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import TextInput from '@/Components/Forms/Controls/TextInput';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';
import { FormEventHandler, useEffect } from 'react';
import { PageProps } from '@/types';
import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';

export interface UpdateProfileInformationFormData {
    namePrefix: string;
    firstName: string;
    middleNames: string[];
    lastName: string;
    nameSuffix: string;
    email: string;
}

export default function UpdateProfileInformation({
    mustVerifyEmail,
    status,
    className = '',
}: {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}) {
    const user = usePage<PageProps>().props.auth.user;

    const {
        data,
        setData,
        patch,
        errors,
        processing,
        recentlySuccessful,
        reset,
        isDirty,
        setDefaults,
    } = useForm<UpdateProfileInformationFormData>({
        namePrefix: user.name.prefix,
        firstName: user.name.firstName,
        middleNames: user.name.middleNames,
        lastName: user.name.lastName,
        nameSuffix: user.name.suffix,
        email: user.email,
    }) as Omit<
        ReturnType<typeof useForm<UpdateProfileInformationFormData>>,
        'setDefaults'
    > & {
        setDefaults: (
            fields: Partial<UpdateProfileInformationFormData>
        ) => void;
    };

    const submit: FormEventHandler = e => {
        e.preventDefault();

        patch(route('profile.update'));
    };

    useEffect(() => {
        setDefaults({
            namePrefix: user.name.prefix,
            firstName: user.name.firstName,
            middleNames: user.name.middleNames,
            lastName: user.name.lastName,
            nameSuffix: user.name.suffix,
            email: user.email,
        });
    }, [user]);

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Profile Information
                </h2>

                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update your account's profile information and email address.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div className="grid grid-cols-1 gap-x-2 gap-y-4 sm:grid-cols-6">
                    {/* Name prefix */}
                    <div className="sm:col-span-2">
                        <InputLabel htmlFor="namePrefix" value="Name prefix" />

                        <TextInput
                            id="namePrefix"
                            name="namePrefix"
                            value={data.namePrefix}
                            className="mt-1 block w-full"
                            autoComplete="namePrefix"
                            isFocused={true}
                            onChange={e => {
                                setData('namePrefix', e.target.value);
                            }}
                            placeholder="e.g. Dr."
                        />

                        <InputError
                            message={errors.namePrefix}
                            className="mt-2"
                        />
                    </div>

                    {/* First name */}
                    <div className="sm:col-span-4">
                        <InputLabel htmlFor="firstName" value="First name" />

                        <TextInput
                            id="firstName"
                            name="firstName"
                            value={data.firstName}
                            className="mt-1 block w-full"
                            autoComplete="firstName"
                            isFocused={true}
                            onChange={e => {
                                setData('firstName', e.target.value);
                            }}
                            placeholder="e.g. John"
                            required
                        />

                        <InputError
                            message={errors.firstName}
                            className="mt-2"
                        />
                    </div>

                    {/* Middle names */}
                    <div className="sm:col-span-6">
                        <InputLabel
                            htmlFor="middleNames"
                            value="Middle names"
                        />

                        <TextChipsInput
                            id="middleNames"
                            name="middleNames"
                            value={data.middleNames}
                            className="mt-1 block w-full"
                            autoComplete="middleNames"
                            isFocused={true}
                            onChange={names => {
                                setData('middleNames', names);
                            }}
                            placeholder="e.g. Adam, Alan, Carl"
                        />
                        <p className="text-xs text-right text-gray-500 dark:text-gray-400 mt-1">
                            Enter your middle names, separated by commas.
                        </p>

                        <InputError
                            message={errors.middleNames}
                            className="mt-2"
                        />
                    </div>

                    {/* Last name */}
                    <div className="sm:col-span-4">
                        <InputLabel htmlFor="lastName" value="Last name" />

                        <TextInput
                            id="lastName"
                            name="lastName"
                            value={data.lastName}
                            className="mt-1 block w-full"
                            autoComplete="lastName"
                            isFocused={true}
                            onChange={e => {
                                setData('lastName', e.target.value);
                            }}
                            placeholder="e.g. Doe"
                            required
                        />

                        <InputError
                            message={errors.lastName}
                            className="mt-2"
                        />
                    </div>

                    {/* Name suffix */}
                    <div className="sm:col-span-2">
                        <InputLabel htmlFor="nameSuffix" value="Name suffix" />

                        <TextInput
                            id="nameSuffix"
                            name="nameSuffix"
                            value={data.nameSuffix}
                            className="mt-1 block w-full"
                            autoComplete="nameSuffix"
                            isFocused={true}
                            onChange={e => {
                                setData('nameSuffix', e.target.value);
                            }}
                            placeholder="e.g. PhD"
                        />

                        <InputError
                            message={errors.nameSuffix}
                            className="mt-2"
                        />
                    </div>

                    {/* E-mail address */}
                    <div className="sm:col-span-6">
                        <InputLabel htmlFor="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            className="mt-1 block w-full"
                            value={data.email}
                            onChange={e => {
                                setData('email', e.target.value);
                            }}
                            required
                            autoComplete="username"
                        />

                        <InputError className="mt-2" message={errors.email} />

                        {mustVerifyEmail && !user.emailVerified && (
                            <div>
                                <p className="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                    Your email address is unverified.
                                    <Link
                                        href={route('verification.send')}
                                        method="post"
                                        as="button"
                                        className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                        Click here to re-send the verification
                                        email.
                                    </Link>
                                </p>

                                {status === 'verification-link-sent' && (
                                    <div className="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                        A new verification link has been sent to
                                        your email address.
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                </div>

                <div className="flex items-center justify-end gap-4">
                    {isDirty && (
                        <SecondaryButton
                            disabled={processing}
                            onClick={() => {
                                reset();
                            }}>
                            Discard changes
                        </SecondaryButton>
                    )}
                    <PrimaryButton disabled={processing}>
                        {processing ? 'Saving changes' : 'Save changes'}
                    </PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0">
                        <p className="text-sm text-gray-600 dark:text-gray-400">
                            Saved.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
