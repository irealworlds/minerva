import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import { FormEventHandler } from 'react';

interface PasswordFormProps {
    data: PasswordFormData;
    setData: <K extends keyof PasswordFormData>(
        key: K,
        value: PasswordFormData[K]
    ) => void;
    errors: Partial<Record<keyof PasswordFormData, string>>;
    disabled?: boolean;

    previousStep: () => void;
    save: () => void;
}

interface PasswordFormData {
    password: string;
    passwordConfirmation: string;
}

export default function PasswordForm({
    data,
    setData,
    errors,
    disabled,
    previousStep,
    save,
}: PasswordFormProps) {
    const submit: FormEventHandler = event => {
        event.preventDefault();
        save();
    };

    return (
        <form onSubmit={submit} className="space-y-6">
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
                    disabled={disabled}
                    onChange={e => {
                        setData('password', e.target.value);
                    }}
                    placeholder="**********"
                    required
                />

                <InputError message={errors.password} className="mt-2" />
            </div>

            {/* Password confirmation*/}
            <div className="sm:col-span-6">
                <InputLabel
                    htmlFor="passwordConfirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="passwordConfirmation"
                    type="password"
                    name="passwordConfirmation"
                    value={data.passwordConfirmation}
                    className="mt-1 block w-full"
                    autoComplete="new-password"
                    disabled={disabled}
                    onChange={e => {
                        setData('passwordConfirmation', e.target.value);
                    }}
                    placeholder="**********"
                    required
                />

                <InputError
                    message={errors.passwordConfirmation}
                    className="mt-2"
                />
            </div>

            {/* Actions */}
            <div className="flex items-center justify-stretch md:justify-end gap-3">
                <SecondaryButton
                    type="button"
                    disabled={disabled}
                    onClick={() => {
                        previousStep();
                    }}>
                    Back
                </SecondaryButton>
                <PrimaryButton type="submit" disabled={disabled}>
                    Save password
                </PrimaryButton>
            </div>
        </form>
    );
}
