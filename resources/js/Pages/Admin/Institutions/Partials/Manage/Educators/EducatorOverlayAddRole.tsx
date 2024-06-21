import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';
import { FormEventHandler } from 'react';
import { useForm } from '@inertiajs/react';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import InputError from '@/Components/Forms/InputError';

interface EducatorOverlayAddRoleProps {
    educatorId: string;
    parentInstitutionId: string;
    setCurrentSection: (newSection: 'readonly') => void;
}
export default function EducatorOverlayAddRole({
    educatorId,
    setCurrentSection,
    parentInstitutionId,
}: EducatorOverlayAddRoleProps) {
    const { data, setData, processing, post, errors } = useForm({
        roles: [] as string[],
    });

    const submit: FormEventHandler = e => {
        post(
            route('admin.institutions.educators.roles.create', {
                institution: parentInstitutionId,
                educator: educatorId,
            })
        );

        e.preventDefault();
    };

    return (
        <div className="sm:px-6 sm:py-5">
            {/* Back to details */}
            <button
                type="button"
                onClick={() => {
                    setCurrentSection('readonly');
                }}
                className="flex items-center gap-2 leading-6 text-gray-500 font-semibold text-sm">
                <ArrowLeftIcon className="size-4" />
                <span>Back to details</span>
            </button>

            <form onSubmit={submit} className="space-y-8 mt-6">
                <div>
                    <TextChipsInput
                        value={data.roles}
                        onChange={roles => {
                            setData('roles', roles);
                        }}
                        disabled={processing}
                        className="w-full"
                        placeholder="Roles, divided by comma"
                    />
                    <InputError message={errors.roles} className="mt-2" />
                </div>

                <div className="flex items-center justify-end gap-2">
                    <PrimaryButton disabled={processing} type="submit">
                        {processing ? 'Adding roles' : 'Add roles'}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    );
}
