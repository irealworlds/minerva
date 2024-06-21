import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import TextInput from '@/Components/Forms/Controls/TextInput';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import React, { FormEventHandler, useEffect, useMemo, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { InformationCircleIcon } from '@heroicons/react/20/solid';

interface StudentGroupAddDisciplineProps {
    group: StudentGroupViewModel;
    setModifyingSection: (section: null) => void;
}

interface DisciplineAdditionFormData {
    selectedDisciplines: DisciplineDto[];
}

export default function StudentGroupAddDiscipline({
    group,
    setModifyingSection,
}: StudentGroupAddDisciplineProps) {
    const { data, setData, processing, post, transform, wasSuccessful } =
        useForm<DisciplineAdditionFormData>({
            selectedDisciplines: [],
        });
    const [searchQuery, setSearchQuery] = useState('');

    const [suggestions, setSuggestions] = useState<DisciplineDto[]>([]);

    const parentInstitutionId = useMemo(() => {
        const parent = group.ancestors.findLast(a => a.type === 'institution');

        if (!parent) {
            throw new Error('Group does not have an institution parent');
        }

        return parent.id;
    }, [group]);

    // Fetch suggestions
    useEffect(() => {
        axios
            .get<{
                disciplines: PaginatedCollection<DisciplineDto>;
            }>(
                route('api.disciplines.index', {
                    search: searchQuery,
                    associatedToInstitutionIds: parentInstitutionId,
                    notAssociatedToStudentGroupIds: group.id,
                })
            )
            .then(response => {
                setSuggestions(response.data.disciplines.data);
            })
            .catch(() => {
                // Do nothing
            });
    }, [searchQuery]);

    // Transform the data
    transform(
        data =>
            ({
                disciplineKeys: data.selectedDisciplines.map(d => d.id),
            }) as unknown as DisciplineAdditionFormData
    );

    // If the form was successful, close the section
    useEffect(() => {
        if (wasSuccessful) {
            setModifyingSection(null);
        }
    }, [wasSuccessful]);

    // Set whether a discipline is selected or not
    function setDisciplineSelected(
        discipline: DisciplineDto,
        selected: boolean
    ) {
        if (selected) {
            setData('selectedDisciplines', [
                ...data.selectedDisciplines.filter(d => d.id !== discipline.id),
                discipline,
            ]);
        } else {
            setData('selectedDisciplines', [
                ...data.selectedDisciplines.filter(d => d.id !== discipline.id),
            ]);
        }
    }

    // Submit the form
    const submit: FormEventHandler = e => {
        e.preventDefault();

        post(
            route('admin.student_groups.disciplines.create', {
                group: group.id,
            })
        );
    };

    return (
        <form onSubmit={submit}>
            {/* Educational offer alert */}
            <div className="rounded-md bg-blue-50 p-4">
                <div className="flex">
                    <div className="flex-shrink-0">
                        <InformationCircleIcon
                            className="h-5 w-5 text-blue-400"
                            aria-hidden="true"
                        />
                    </div>
                    <div className="ml-3">
                        <p className="text-sm text-blue-700">
                            If you can't find a discipline, make sure it is part
                            of this institution's educational offer.
                        </p>
                        <p className="mt-3 text-sm">
                            <Link
                                href={route(
                                    'admin.institutions.show.disciplines',
                                    {
                                        institution: parentInstitutionId,
                                    }
                                )}
                                className="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600">
                                View educational offer
                                <span aria-hidden="true"> &rarr;</span>
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
            <div className="mt-2">
                <div className="flex items-center gap-2">
                    {/* Back button */}
                    <button
                        type="button"
                        onClick={() => {
                            setModifyingSection(null);
                        }}
                        disabled={processing}
                        className="relative flex size-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span className="absolute -inset-1.5" />
                        <ArrowLeftIcon className="size-5" aria-hidden="true" />
                        <span className="sr-only">Back</span>
                    </button>

                    {/* Title */}
                    <h3 className="font-medium text-gray-900">
                        Adding discipline
                    </h3>
                </div>
                <div className="mt-2 mb-4 border-b border-t border-gray-200">
                    {/* Search */}
                    <div className="py-3 text-sm font-medium">
                        <TextInput
                            id="search"
                            type="search"
                            name="search"
                            value={searchQuery}
                            className="mt-1 block w-full text-gray-900"
                            placeholder="Search institution disciplines"
                            onChange={e => {
                                setSearchQuery(e.target.value);
                            }}
                        />
                    </div>
                    <fieldset className="divide-y divide-gray-200">
                        {/* Disciplines already associated */}
                        {group.disciplines.map(
                            (existingDiscipline, personIdx) => (
                                <div
                                    key={personIdx}
                                    className="relative flex items-start py-4">
                                    <div className="min-w-0 flex-1 text-sm leading-6">
                                        <label
                                            htmlFor={`person-${existingDiscipline.id}`}
                                            className="select-none font-medium text-gray-900">
                                            {existingDiscipline.name}
                                        </label>
                                    </div>
                                    <div className="ml-3 flex h-6 items-center">
                                        <input
                                            id={`person-${existingDiscipline.id}`}
                                            name={`person-${existingDiscipline.id}`}
                                            checked={true}
                                            disabled={true}
                                            type="checkbox"
                                            className="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-not-allowed opacity-20"
                                        />
                                    </div>
                                </div>
                            )
                        )}

                        {/* New disciplines */}
                        {[
                            ...data.selectedDisciplines,
                            ...suggestions.filter(
                                suggestion =>
                                    !data.selectedDisciplines.find(
                                        selected =>
                                            selected.id === suggestion.id
                                    )
                            ),
                        ].map((suggestedDiscipline, personIdx) => (
                            <div
                                key={personIdx}
                                className="relative flex items-start py-4">
                                <div className="min-w-0 flex-1 text-sm leading-6">
                                    <label
                                        htmlFor={`person-${suggestedDiscipline.id}`}
                                        className="select-none font-medium text-gray-900">
                                        {suggestedDiscipline.name}
                                    </label>
                                </div>
                                <div className="ml-3 flex h-6 items-center">
                                    <input
                                        id={`person-${suggestedDiscipline.id}`}
                                        name={`person-${suggestedDiscipline.id}`}
                                        checked={
                                            !!data.selectedDisciplines.find(
                                                selected =>
                                                    selected.id ===
                                                    suggestedDiscipline.id
                                            )
                                        }
                                        type="checkbox"
                                        onChange={event => {
                                            setDisciplineSelected(
                                                suggestedDiscipline,
                                                event.target.checked
                                            );
                                        }}
                                        className="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer"
                                    />
                                </div>
                            </div>
                        ))}
                    </fieldset>
                </div>
            </div>

            {/* Actions */}
            <div className="flex items-center gap-3">
                <SecondaryButton
                    type="button"
                    disabled={processing}
                    onClick={() => {
                        setModifyingSection(null);
                    }}
                    className="grow justify-center">
                    Cancel
                </SecondaryButton>
                <PrimaryButton
                    type="submit"
                    disabled={
                        processing || data.selectedDisciplines.length === 0
                    }
                    className="grow justify-center">
                    Add {data.selectedDisciplines.length} disciplines
                </PrimaryButton>
            </div>
        </form>
    );
}
