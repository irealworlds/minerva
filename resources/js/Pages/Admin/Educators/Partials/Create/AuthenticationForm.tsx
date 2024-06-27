import { useEffect, useState } from 'react';
import { AuthenticationFormData } from '@/Pages/Admin/Educators/Create';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';

interface AuthenticationFormProps {
    className?: string;
    data: AuthenticationFormData;
    onChange: (newValue: AuthenticationFormData) => void;
    errors: Partial<Record<keyof AuthenticationFormData, string>>;
    disabled?: boolean;
}

export default function AuthenticationForm({
    className,
    data,
    onChange,
    errors,
    disabled,
}: AuthenticationFormProps) {
    const [selectedAuthenticationMode, setSelectedAuthenticationMode] =
        useState<'password-define-now' | 'password-send-email'>(
            'password-send-email'
        );

    useEffect(() => {
        if (selectedAuthenticationMode === 'password-define-now') {
            onChange({
                password: '',
            });
        } else {
            onChange({
                password: undefined,
            });
        }
    }, [selectedAuthenticationMode]);

    return (
        <div className={className}>
            <div className="px-4 py-6 sm:p-8">
                <fieldset>
                    <div className="space-y-5">
                        <div className="flex flex-col-reverse items-start gap-y-3 md:flex-row md:justify-between">
                            <div className="relative flex items-start">
                                <div className="flex h-6 items-center">
                                    <input
                                        id="authentication-mode-password-send-email"
                                        name="plan"
                                        type="radio"
                                        checked={
                                            selectedAuthenticationMode ===
                                            'password-send-email'
                                        }
                                        disabled={disabled}
                                        onChange={event => {
                                            if (event.target.checked) {
                                                setSelectedAuthenticationMode(
                                                    'password-send-email'
                                                );
                                            }
                                        }}
                                        className="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                    />
                                </div>
                                <div className="ml-3 text-sm leading-6">
                                    <label
                                        htmlFor="authentication-mode-password-send-email"
                                        className="font-medium text-gray-900">
                                        Authentication via password
                                    </label>
                                    <p className="text-gray-500">
                                        which the educator will set themselves
                                        upon receiving an e-mail.
                                    </p>
                                </div>
                            </div>
                            <span className="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                Recommended
                            </span>
                        </div>

                        <div className="relative flex items-start">
                            <div className="flex h-6 items-center">
                                <input
                                    id="authentication-mode-password-define-now"
                                    name="plan"
                                    type="radio"
                                    checked={
                                        selectedAuthenticationMode ===
                                        'password-define-now'
                                    }
                                    disabled={disabled}
                                    onChange={event => {
                                        if (event.target.checked) {
                                            setSelectedAuthenticationMode(
                                                'password-define-now'
                                            );
                                        }
                                    }}
                                    className="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                />
                            </div>
                            <div className="ml-3 text-sm leading-6">
                                <label
                                    htmlFor="authentication-mode-password-define-now"
                                    className="font-medium text-gray-900">
                                    Authentication via password
                                </label>
                                <p className="text-gray-500">
                                    which you will be setting for the educator
                                    now
                                </p>

                                {/* Password */}
                                {selectedAuthenticationMode ===
                                    'password-define-now' && (
                                    <div className="mt-6">
                                        <InputLabel
                                            htmlFor="password"
                                            value="Password"
                                        />

                                        <TextInput
                                            id="password"
                                            type="password"
                                            name="password"
                                            value={data.password}
                                            className="mt-1 block w-full"
                                            autoComplete="new-password"
                                            disabled={disabled}
                                            onChange={e => {
                                                onChange({
                                                    password: e.target.value,
                                                });
                                            }}
                                            placeholder="**********"
                                            required
                                        />

                                        <InputError
                                            message={errors.password}
                                            className="mt-2"
                                        />
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    );
}
