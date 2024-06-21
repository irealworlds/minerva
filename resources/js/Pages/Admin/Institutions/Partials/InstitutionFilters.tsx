import React, { FormEvent, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';

interface InstitutionFiltersProps {
    filters: {
        search: string | null;
    };
}

interface FiltersForm {
    search?: string;
}

export default function InstitutionFilters({
    filters,
}: InstitutionFiltersProps) {
    const {
        data,
        setData,
        get,
        errors,
        isDirty,
        processing,
        reset,
        transform,
    } = useForm<FiltersForm>({
        search: '',
    });

    transform(formData => {
        const filterParams: {
            [ignored in keyof typeof formData]?: string;
        } = {};
        if (formData.search?.length) {
            filterParams.search = formData.search;
        }
        return filterParams;
    });

    function updateSearch(e: FormEvent<HTMLFormElement>) {
        get(route('admin.institutions.index'), {
            preserveScroll: true,
        });
        e.preventDefault();
    }

    useEffect(() => {
        setData('search', filters.search ?? '');
    }, [filters]);

    return (
        <>
            <form onSubmit={updateSearch} className="w-full max-w-xs">
                <div className="w-full">
                    <div className="bg-white rounded-lg shadow px-4 py-5">
                        <div>
                            <InputLabel htmlFor="query" value="Search" />

                            <TextInput
                                id="query"
                                type="search"
                                name="query"
                                value={data.search}
                                className="mt-1 block w-full"
                                placeholder="Filter by query"
                                onChange={e => {
                                    setData('search', e.target.value);
                                }}
                            />

                            <InputError
                                message={errors.search}
                                className="mt-2"
                            />
                        </div>
                    </div>

                    <div className="mt-4 flex items-center gap-4 flex-wrap">
                        {isDirty && (
                            <SecondaryButton
                                className="grow justify-center"
                                type="button"
                                onClick={() => {
                                    reset();
                                }}
                                disabled={processing}>
                                Clear
                            </SecondaryButton>
                        )}
                        <PrimaryButton
                            className="grow justify-center"
                            type="submit"
                            disabled={processing}>
                            Update results
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </>
    );
}
