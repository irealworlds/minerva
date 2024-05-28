import InstitutionDisciplineSelector from '@/Pages/Institutions/Components/InstitutionDisciplineSelector';
import { DisciplineDto } from '@/types/dtos/discipline.dto';
import { MouseEvent, useState } from 'react';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { useForm } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

interface AddInstitutionDisciplineProps {
    institution: InstitutionViewModel;
}

export default function AddInstitutionDiscipline({
    institution,
}: AddInstitutionDisciplineProps) {
    const [selectedDiscipline, setSelectedDiscipline] =
        useState<DisciplineDto | null>(null);
    const { processing, post } = useForm();

    function submit(e: MouseEvent<HTMLButtonElement>): void {
        if (!selectedDiscipline) {
            throw new Error('Discipline is required');
        }

        e.preventDefault();
        post(
            route('institutions.show.disciplines.store', {
                institution: institution.id,
                discipline: selectedDiscipline.id,
            }),
            {
                preserveState: false,
            }
        );
    }
    return (
        <div className="flex items-center gap-2">
            <InstitutionDisciplineSelector
                className="grow"
                value={selectedDiscipline}
                disabled={processing}
                institution={institution}
                onChange={newValue => {
                    setSelectedDiscipline(newValue);
                }}
            />
            <PrimaryButton
                type="button"
                disabled={processing || !selectedDiscipline}
                onClick={e => {
                    submit(e);
                }}>
                Add
            </PrimaryButton>
        </div>
    );
}
