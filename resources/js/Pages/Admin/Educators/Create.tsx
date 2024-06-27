import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import IdentityForm from '@/Pages/Admin/Educators/Partials/Create/IdentityForm';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { FormEventHandler } from 'react';
import AuthenticationForm from '@/Pages/Admin/Educators/Partials/Create/AuthenticationForm';

export interface IdentityFormData {
    idNumber: string;
    namePrefix: string;
    firstName: string;
    middleNames: string[];
    lastName: string;
    nameSuffix: string;
    email: string;
}

export interface AuthenticationFormData {
    password: string | undefined;
}

type EducatorFormData = IdentityFormData & AuthenticationFormData;

export default function Create({ auth }: PageProps) {
    const { data, setData, errors, processing, post } =
        useForm<EducatorFormData>({
            idNumber: '',
            namePrefix: '',
            firstName: '',
            middleNames: [],
            lastName: '',
            nameSuffix: '',
            email: '',
            password: undefined,
        });

    const submit: FormEventHandler = event => {
        post(route('admin.educators.store'));

        event.preventDefault();
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="New educator" />

            <form onSubmit={submit}>
                <div className="space-y-10 divide-y divide-gray-900/10">
                    <div className="relative grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                        <div>
                            <div className="px-4 sm:px-0 sticky top-20">
                                <h2 className="text-base font-semibold leading-7 text-gray-900">
                                    Identity
                                </h2>
                                <p className="mt-1 text-sm leading-6 text-gray-600">
                                    Details about the educator's personal
                                    identity.
                                </p>
                            </div>
                        </div>

                        <IdentityForm
                            data={data}
                            onChange={newValue => {
                                setData(currentData => ({
                                    ...currentData,
                                    ...newValue,
                                }));
                            }}
                            errors={errors}
                            disabled={processing}
                            className="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2"
                        />
                    </div>

                    <div className="relative grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3 pt-8">
                        <div>
                            <div className="px-4 sm:px-0 sticky top-20">
                                <h2 className="text-base font-semibold leading-7 text-gray-900">
                                    Authentication
                                </h2>
                                <p className="mt-1 text-sm leading-6 text-gray-600">
                                    Details about how the educator is going to
                                    sign into the system.
                                </p>
                            </div>
                        </div>

                        <AuthenticationForm
                            data={data}
                            onChange={newValue => {
                                setData(currentData => ({
                                    ...currentData,
                                    ...newValue,
                                }));
                            }}
                            errors={errors}
                            disabled={processing}
                            className="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2"
                        />
                    </div>
                </div>
                <div className="sticky bottom-0 bg-white border-t rounded-lg shadow px-4 flex items-center justify-end gap-x-6 py-4 sm:px-8 mt-10">
                    <PrimaryButton type="submit" disabled={processing}>
                        {processing ? 'Saving educator' : 'Save educator'}
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticatedLayout>
    );
}
