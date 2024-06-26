import { FormEventHandler, useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Forms/InputError';
import InputLabel from '@/Components/Forms/InputLabel';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import TextInput from '@/Components/Forms/Controls/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';

export interface RegistrationFormData {
    idNumber: string;
    namePrefix: string;
    firstName: string;
    middleNames: string[];
    lastName: string;
    nameSuffix: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export default function Register() {
    const { data, setData, post, processing, errors, reset } =
        useForm<RegistrationFormData>({
            idNumber: '',
            namePrefix: '',
            firstName: '',
            middleNames: [],
            lastName: '',
            nameSuffix: '',
            email: '',
            password: '',
            password_confirmation: '',
        });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit: FormEventHandler = e => {
        e.preventDefault();

        post(route('register'));
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <form onSubmit={submit}>
                <div className="grid grid-cols-1 gap-x-2 gap-y-4 sm:grid-cols-6">
                    {/* Id number */}
                    <div className="col-span-full">
                        <InputLabel htmlFor="idNumber" value="Id number" />

                        <TextInput
                            id="idNumber"
                            name="idNumber"
                            value={data.idNumber}
                            className="mt-1 block w-full"
                            autoComplete="idNumber"
                            isFocused={true}
                            onChange={e => {
                                setData('idNumber', e.target.value);
                            }}
                            placeholder="e.g. 1234567890123"
                        />

                        <InputError
                            message={errors.idNumber}
                            className="mt-2"
                        />
                    </div>

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
                        <InputLabel htmlFor="email" value="E-mail address" />

                        <TextInput
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="username"
                            onChange={e => {
                                setData('email', e.target.value);
                            }}
                            placeholder="e.g. you@example.com"
                            required
                        />

                        <InputError message={errors.email} className="mt-2" />
                    </div>

                    {/* Password */}
                    <div className="sm:col-span-6">
                        <InputLabel htmlFor="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            autoComplete="new-password"
                            onChange={e => {
                                setData('password', e.target.value);
                            }}
                            placeholder="**********"
                            required
                        />

                        <InputError
                            message={errors.password}
                            className="mt-2"
                        />
                    </div>

                    {/* Password confirmation*/}
                    <div className="sm:col-span-6">
                        <InputLabel
                            htmlFor="password_confirmation"
                            value="Confirm Password"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            value={data.password_confirmation}
                            className="mt-1 block w-full"
                            autoComplete="new-password"
                            onChange={e => {
                                setData(
                                    'password_confirmation',
                                    e.target.value
                                );
                            }}
                            placeholder="**********"
                            required
                        />

                        <InputError
                            message={errors.password_confirmation}
                            className="mt-2"
                        />
                    </div>
                </div>

                <div className="flex items-center justify-end border-t mt-6 pt-6">
                    <Link
                        href={route('login')}
                        className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        Already registered?
                    </Link>

                    <PrimaryButton className="ms-4" disabled={processing}>
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
