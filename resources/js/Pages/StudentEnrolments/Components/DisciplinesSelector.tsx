import { useMemo, useState } from 'react';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import {
    ArrowLeftIcon,
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
} from '@heroicons/react/24/outline';
import { ArrowRightIcon } from '@heroicons/react/20/solid';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { SelectableEnrolmentDiscipline } from '@/Pages/StudentEnrolments/Partials/NewEnrolmentDisciplinesForm';

interface DisciplinesSelectorProps {
    unselectedDisciplines: SelectableEnrolmentDiscipline[];
    selectedDisciplines: SelectableEnrolmentDiscipline[];
    onChange: (
        newValue: DisciplinesSelectorProps['selectedDisciplines']
    ) => void;
}

export default function DisciplinesSelector({
    unselectedDisciplines,
    selectedDisciplines,
    onChange,
}: DisciplinesSelectorProps) {
    const [selectedItemId, setSelectedItemId] = useState<string | null>(null);
    const selectedItemHasBeenAdded = useMemo(() => {
        if (!selectedItemId) return false;
        return selectedDisciplines.some(
            discipline => discipline.id === selectedItemId
        );
    }, [selectedItemId, unselectedDisciplines, selectedDisciplines]);

    function markSelected(id: string) {
        const discipline = unselectedDisciplines.find(d => d.id === id);
        if (!discipline) return;
        onChange([discipline, ...selectedDisciplines]);
        setSelectedItemId(null);
    }
    function markAllSelected() {
        onChange([...unselectedDisciplines, ...selectedDisciplines]);
        setSelectedItemId(null);
    }
    function markUnselected(id: string) {
        onChange(selectedDisciplines.filter(d => d.id !== id));
        setSelectedItemId(null);
    }
    function markAllUnselected() {
        onChange([]);
        setSelectedItemId(null);
    }

    function isSelected(discipline: SelectableEnrolmentDiscipline): boolean {
        return selectedItemId === discipline.id;
    }

    return (
        <div className="flex gap-4">
            <div className="grow max-w-xl flex flex-col">
                <h3 className="font-semibold">Not selected</h3>
                <p className="mt-1 text-sm text-gray-500">
                    Disciplines associated to this student group but which the
                    new student will not be studying.
                </p>
                <div className="border rounded-md shadow mt-5 overflow-hidden grow">
                    {unselectedDisciplines.length === 0 ? (
                        <div className="flex flex-col items-center justify-center p-5 size-full">
                            <h4>No disciplines selected</h4>
                            <p className="text-sm text-gray-500">
                                Use the buttons to add disciplines.
                            </p>
                        </div>
                    ) : (
                        <ul>
                            {unselectedDisciplines.map(discipline => (
                                <li key={discipline.id}>
                                    <button
                                        type="button"
                                        className={combineClassNames(
                                            'block w-full text-left text-sm px-3 py-3',
                                            isSelected(discipline)
                                                ? 'bg-indigo-500 text-white'
                                                : 'hover:bg-gray-100'
                                        )}
                                        onClick={() => {
                                            setSelectedItemId(discipline.id);
                                        }}>
                                        <h5 className="font-semibold">
                                            {discipline.disciplineName}
                                        </h5>
                                        <p
                                            className={combineClassNames(
                                                'text-sm',
                                                isSelected(discipline)
                                                    ? 'text-indigo-200'
                                                    : 'text-gray-500'
                                            )}>
                                            Taught by {discipline.educatorName}
                                        </p>
                                    </button>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
            <div className="flex flex-col gap-4">
                {/* Select */}
                <SecondaryButton
                    onClick={markAllSelected}
                    disabled={unselectedDisciplines.length === 0}>
                    <ChevronDoubleRightIcon className="size-5" />
                </SecondaryButton>
                <PrimaryButton
                    onClick={() => {
                        selectedItemId && markSelected(selectedItemId);
                    }}
                    disabled={!selectedItemId || selectedItemHasBeenAdded}>
                    <ArrowRightIcon className="size-5" />
                </PrimaryButton>

                {/* Unselect */}
                <PrimaryButton
                    onClick={() => {
                        selectedItemId && markUnselected(selectedItemId);
                    }}
                    disabled={!selectedItemId || !selectedItemHasBeenAdded}>
                    <ArrowLeftIcon className="size-5" />
                </PrimaryButton>
                <SecondaryButton
                    onClick={markAllUnselected}
                    disabled={selectedDisciplines.length === 0}>
                    <ChevronDoubleLeftIcon className="size-5" />
                </SecondaryButton>
            </div>
            <div className="grow max-w-xl flex flex-col">
                <h3 className="font-semibold">Selected</h3>
                <p className="mt-1 text-sm text-gray-500">
                    Disciplines associated to this student group which the new
                    student will be studying
                </p>
                <div className="border rounded-md overflow-hidden shadow mt-5 grow">
                    {selectedDisciplines.length === 0 ? (
                        <div className="flex flex-col items-center justify-center p-5 size-full">
                            <h4>No disciplines selected</h4>
                            <p className="text-sm text-gray-500">
                                Use the buttons to add disciplines.
                            </p>
                        </div>
                    ) : (
                        <ul>
                            {selectedDisciplines.map(discipline => (
                                <li key={discipline.id}>
                                    <button
                                        type="button"
                                        className={combineClassNames(
                                            'block w-full text-left text-sm px-3 py-3',
                                            isSelected(discipline)
                                                ? 'bg-indigo-500 text-white'
                                                : 'hover:bg-gray-100'
                                        )}
                                        onClick={() => {
                                            setSelectedItemId(discipline.id);
                                        }}>
                                        <h5 className="font-semibold">
                                            {discipline.disciplineName}
                                        </h5>
                                        <p
                                            className={combineClassNames(
                                                'text-sm',
                                                isSelected(discipline)
                                                    ? 'text-indigo-200'
                                                    : 'text-gray-500'
                                            )}>
                                            Taught by {discipline.educatorName}
                                        </p>
                                    </button>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </div>
    );
}
