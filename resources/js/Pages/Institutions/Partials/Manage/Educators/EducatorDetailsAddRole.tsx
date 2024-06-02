import { PlusIcon } from '@heroicons/react/20/solid';
import TextChipsInput from '@/Components/Forms/Controls/TextChipsInput';
import { FormEventHandler, useContext, useEffect } from 'react';
import { InstitutionManagementContext } from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import { EducatorManagementContext } from '@/Pages/Institutions/Partials/Manage/Educators/EducatorDetailsOverlay';
import { useForm } from '@inertiajs/react';
import InputError from '@/Components/Forms/InputError';

export default function EducatorDetailsAddRole() {
    const { data, setData, processing, post, errors, reset, wasSuccessful } =
        useForm<{
            roles: string[];
        }>({
            roles: [],
        });
    const { institution } = useContext(InstitutionManagementContext);
    const { educator } = useContext(EducatorManagementContext);

    const submit: FormEventHandler<HTMLFormElement> = e => {
        e.preventDefault();

        if (!institution) {
            throw new Error('Institution is not set');
        }
        if (!educator) {
            throw new Error('Educator is not set');
        }

        post(
            route('institutions.educators.roles.create', {
                institution: institution.id,
                educator: educator.id,
            })
        );
    };

    useEffect(() => {
        if (wasSuccessful) {
            reset();
        }
    }, [wasSuccessful]);

    return (
        <form onSubmit={submit}>
            <table className="group">
                <tbody>
                    <tr>
                        <td>
                            <span className="shrink-0 flex size-8 items-center justify-center rounded-full border-2 border-dashed border-gray-300 text-gray-400 group-focus-within:text-indigo-600 group-focus-within:border-indigo-600 transition-colors">
                                <PlusIcon
                                    className="size-5"
                                    aria-hidden="true"
                                />
                            </span>
                        </td>
                        <td className="w-full pl-2">
                            <TextChipsInput
                                value={data.roles}
                                onChange={roles => {
                                    setData('roles', roles);
                                }}
                                disabled={processing}
                                className="w-full"
                                placeholder="Roles, divided by comma"
                            />
                        </td>
                        <td className="pl-4">
                            <button
                                type="submit"
                                disabled={processing}
                                className="whitespace-nowrap rounded-md bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50">
                                {processing ? 'Adding role' : 'Add role'}
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <InputError message={errors.roles} />
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </form>
    );
}
