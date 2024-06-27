import { Head, useForm } from '@inertiajs/react';
import Guest from '@/Layouts/GuestLayout';
import DataConfirmationForm from '@/Pages/Identity/Password/Partials/DataConfirmationForm';
import { PageProps } from '@/types';
import { useState } from 'react';
import PasswordForm from '@/Pages/Identity/Password/Partials/PasswordForm';

type CreatePageProps = PageProps<{
    identity: {
        key: string;
        idNumber: string;
        name: string;
        emailAddress: string;
    };
    actionUri: string;
}>;

export default function Create({ identity, actionUri }: CreatePageProps) {
    const [currentStep, setCurrentStep] = useState<
        'confirm-data' | 'set-password'
    >('confirm-data');

    const { data, setData, errors, processing, post } = useForm<{
        password: string;
        passwordConfirmation: string;
    }>({
        password: '',
        passwordConfirmation: '',
    });

    function renderCurrentStep() {
        switch (currentStep) {
            case 'confirm-data':
                return (
                    <DataConfirmationForm
                        data={identity}
                        onAdvance={() => {
                            setCurrentStep('set-password');
                        }}
                    />
                );
            case 'set-password':
                return (
                    <PasswordForm
                        data={data}
                        setData={setData}
                        errors={errors}
                        disabled={processing}
                        previousStep={() => {
                            setCurrentStep('confirm-data');
                        }}
                        save={savePassword}
                    />
                );
        }
    }

    function savePassword() {
        post(actionUri);
    }

    return (
        <Guest>
            <Head title="Create" />

            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 className="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                    Set up your password
                </h2>
                <p className="text-center text-sm text-gray-500">
                    {currentStep === 'confirm-data' && (
                        <>
                            <span className="font-semibold">Step 1:</span>{' '}
                            Confirm your personal information
                        </>
                    )}

                    {currentStep === 'set-password' && (
                        <>
                            <span className="font-semibold">Step 2:</span>{' '}
                            Confirm your personal information
                        </>
                    )}
                </p>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                {renderCurrentStep()}
            </div>
        </Guest>
    );
}
