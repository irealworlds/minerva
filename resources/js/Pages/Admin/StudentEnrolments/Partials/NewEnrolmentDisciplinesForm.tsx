import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import {
    ArrowRightIcon,
    ExclamationCircleIcon,
    ExclamationTriangleIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';
import React, { useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import Spinner from '@/Components/Spinner';
import DisciplinesSelector from '@/Pages/Admin/StudentEnrolments/Components/DisciplinesSelector';
import { StudentGroupDisciplineDto } from '@/types/dtos/student-group-discipline.dto';

interface DisciplineFormData {
    disciplines: SelectableEnrolmentDiscipline[];
}

interface NewEnrolmentDisciplinesFormProps {
    studentGroup: { id: string; name: string } | null;
    data: DisciplineFormData;
    setData: <K extends keyof DisciplineFormData>(
        key: K,
        value: DisciplineFormData[K]
    ) => void;
    errors: Partial<Record<keyof DisciplineFormData, string>>;
    onAdvance: () => void;
    onPreviousRequested?: () => void;
    disabled?: boolean;
}

export interface SelectableEnrolmentDiscipline {
    id: string;
    disciplineId: string;
    disciplineName: string;
    educatorId: string;
    educatorName: string;
}

export default function NewEnrolmentDisciplinesForm({
    data,
    setData,
    studentGroup,
    onAdvance,
    onPreviousRequested,
    disabled,
}: NewEnrolmentDisciplinesFormProps) {
    const [disciplines, setDisciplines] = useState<
        SelectableEnrolmentDiscipline[]
    >([]);
    const [loading, setLoading] = useState(false);
    const [loadingFailed, setLoadingFailed] = useState(false);

    // Load disciplines for the student group
    useEffect(() => {
        if (!studentGroup) {
            return;
        }
        setLoading(true);
        setLoadingFailed(false);
        setDisciplines([]);

        const fetchDisciplinesForPage = async (page: number) => {
            const response = await axios.get<{
                results: PaginatedCollection<StudentGroupDisciplineDto>;
            }>(
                route('api.student_groups.disciplines.index', {
                    studentGroup: studentGroup.id,
                    page,
                    pageSize: 100,
                })
            );
            return response.data;
        };

        const fetchAllPages = async () => {
            const response = await fetchDisciplinesForPage(1);
            const results = response.results.data;

            if (response.results.last_page > response.results.current_page) {
                for (
                    let i = response.results.current_page + 1;
                    i < response.results.last_page;
                    i++
                ) {
                    const pageResponse = await fetchDisciplinesForPage(i);

                    results.push(...pageResponse.results.data);
                }
            }

            return results;
        };

        fetchAllPages()
            .then(results => {
                // Flatten the results into a single array of selectable disciplines
                setDisciplines(
                    results.reduce<SelectableEnrolmentDiscipline[]>(
                        (accumulator, current) => {
                            accumulator.push(
                                ...current.educators.map(
                                    (
                                        educator
                                    ): SelectableEnrolmentDiscipline => ({
                                        id: crypto.randomUUID(),
                                        disciplineId: current.id,
                                        disciplineName: current.name,
                                        educatorId: educator.educatorId,
                                        educatorName: educator.educatorName,
                                    })
                                )
                            );
                            return accumulator;
                        },
                        []
                    )
                );

                // Reset loading state
                setLoading(false);
                setLoadingFailed(false);
            })
            .catch(() => {
                setLoadingFailed(true);
            });
    }, [studentGroup]);

    const unselectedDisciplines = useMemo(() => {
        return disciplines.filter(
            d => !data.disciplines.some(selected => selected.id === d.id)
        );
    }, [data, disciplines]);

    const hasSelectedDuplicateDisciplines = useMemo(() => {
        return data.disciplines.some(
            (discipline, index) =>
                data.disciplines.findIndex(
                    d => d.disciplineId === discipline.disciplineId
                ) !== index
        );
    }, [data]);

    return (
        <>
            <div className="space-y-12 sm:space-y-16">
                <div>
                    <h2 className="text-base font-semibold leading-7 text-gray-900">
                        Disciplines
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                        Select which of the disciplines offered at this student
                        group the student is going to be studying.
                    </p>

                    <div className="mt-10">
                        {studentGroup ? (
                            loading ? (
                                <Spinner className="size-12 mx-auto" />
                            ) : loadingFailed ? (
                                <div className="text-red-500">
                                    <ExclamationCircleIcon className="size-12 mx-auto" />
                                    <h3 className="text-center">
                                        Loading failed
                                    </h3>
                                </div>
                            ) : (
                                <DisciplinesSelector
                                    unselectedDisciplines={
                                        unselectedDisciplines
                                    }
                                    selectedDisciplines={data.disciplines}
                                    onChange={newValue => {
                                        setData('disciplines', newValue);
                                    }}
                                />
                            )
                        ) : (
                            <div className="text-center container mx-auto">
                                <UserGroupIcon className="mx-auto size-12 text-gray-400" />
                                <h3 className="mt-2 text-sm font-semibold text-gray-900">
                                    No student group
                                </h3>
                                <p className="mt-1 text-sm text-gray-500">
                                    Select the student group you want to enrol
                                    the student in before adding disciplines.
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {hasSelectedDuplicateDisciplines && (
                <div className="rounded-md bg-yellow-50 p-4 mt-8">
                    <div className="flex">
                        <div className="flex-shrink-0">
                            <ExclamationTriangleIcon
                                className="h-5 w-5 text-yellow-400"
                                aria-hidden="true"
                            />
                        </div>
                        <div className="ml-3">
                            <h3 className="text-sm font-medium text-yellow-800">
                                Duplicate disciplines
                            </h3>
                            <div className="mt-2 text-sm text-yellow-700">
                                <p>
                                    In your current selection, this student
                                    would be studying the same discipline under
                                    different educators. This may be a mistake,
                                    please review the list of disciplines
                                    selected and remove any duplicates.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <div className="mt-6 flex items-center justify-end gap-x-3">
                {onPreviousRequested && (
                    <SecondaryButton
                        disabled={disabled}
                        type="button"
                        onClick={() => {
                            onPreviousRequested();
                        }}>
                        Back
                    </SecondaryButton>
                )}
                <PrimaryButton
                    disabled={disabled}
                    type="submit"
                    onClick={() => {
                        onAdvance();
                    }}>
                    Advance
                    <ArrowRightIcon className="size-4 ml-2" />
                </PrimaryButton>
            </div>
        </>
    );
}
