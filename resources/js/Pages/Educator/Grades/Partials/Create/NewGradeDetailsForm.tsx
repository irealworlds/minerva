import { combineClassNames } from '@/utils/combine-class-names.function';
import InputLabel from '@/Components/Forms/InputLabel';
import TextInput from '@/Components/Forms/Controls/TextInput';
import InputError from '@/Components/Forms/InputError';
import TextareaInput from '@/Components/Forms/Controls/TextareaInput';

interface NewGradeDetailsFormProps {
    className?: string;
    data: GradeDetailsFormData;
    onChange: <K extends keyof GradeDetailsFormData>(
        key: K,
        newValue: GradeDetailsFormData[K]
    ) => void;
    errors: Partial<Record<keyof GradeDetailsFormData, string>>;
}

interface GradeDetailsFormData {
    awardedPoints: number;
    maximumPoints: number;
    notes: string;
    awardedAt: string;
}

export default function NewGradeDetailsForm({
    className,
    data,
    onChange,
    errors,
}: NewGradeDetailsFormProps) {
    return (
        <div
            className={combineClassNames(
                'bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl',
                className
            )}>
            <div className="px-4 py-6 sm:p-8">
                <div className="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    {/* Awarded points */}
                    <div className="sm:col-span-4">
                        <InputLabel htmlFor="awardedPoints">
                            Awarded points
                        </InputLabel>
                        <TextInput
                            id="awardedPoints"
                            type="number"
                            name="awardedPoints"
                            value={
                                isNaN(data.awardedPoints)
                                    ? ''
                                    : data.awardedPoints
                            }
                            className="mt-1 block w-full"
                            onChange={e => {
                                if (e.target.value.trim().length === 0) {
                                    onChange('awardedPoints', NaN);
                                } else {
                                    const points = parseFloat(e.target.value);
                                    if (!isNaN(points)) {
                                        onChange('awardedPoints', points);
                                    }
                                }
                            }}
                            step={0.01}
                            max={
                                isNaN(data.maximumPoints)
                                    ? undefined
                                    : data.maximumPoints
                            }
                            min={0}
                            placeholder="Awarded points"
                        />
                        {errors.awardedPoints ? (
                            <InputError
                                message={errors.awardedPoints}
                                className="mt-2"
                            />
                        ) : (
                            <p
                                className="mt-2 text-sm text-gray-500"
                                id="awardedPoints-description">
                                The amount of points awarded for this grade.
                            </p>
                        )}
                    </div>

                    {/* Maximum points */}
                    <div className="sm:col-span-4">
                        <InputLabel htmlFor="maximumPoints">
                            Maximum points
                        </InputLabel>
                        <TextInput
                            id="maximumPoints"
                            type="number"
                            name="maximumPoints"
                            value={
                                isNaN(data.maximumPoints)
                                    ? ''
                                    : data.maximumPoints
                            }
                            className="mt-1 block w-full"
                            onChange={e => {
                                if (e.target.value.trim().length === 0) {
                                    onChange('maximumPoints', NaN);
                                } else {
                                    const points = parseFloat(e.target.value);
                                    if (!isNaN(points)) {
                                        onChange('maximumPoints', points);
                                    }
                                }
                            }}
                            step={0.01}
                            min={
                                isNaN(data.awardedPoints)
                                    ? 0
                                    : data.awardedPoints
                            }
                            placeholder="Maximum points"
                        />
                        {errors.maximumPoints ? (
                            <InputError
                                message={errors.maximumPoints}
                                className="mt-2"
                            />
                        ) : (
                            <p
                                className="mt-2 text-sm text-gray-500"
                                id="maximumPoints-description">
                                The maximum amount of points possible for this
                                grade.
                            </p>
                        )}
                    </div>

                    {/* Maximum points */}
                    <div className="sm:col-span-4">
                        <InputLabel htmlFor="awardedAt">Awarded at</InputLabel>
                        <TextInput
                            id="awardedAt"
                            type="date"
                            name="awardedAt"
                            value={data.awardedAt}
                            className="mt-1 block w-full"
                            onChange={e => {
                                onChange('awardedAt', e.target.value);
                            }}
                            placeholder="Awarded at"
                        />
                        {errors.awardedAt ? (
                            <InputError
                                message={errors.awardedAt}
                                className="mt-2"
                            />
                        ) : (
                            <p
                                className="mt-2 text-sm text-gray-500"
                                id="awardedAt-description">
                                The date and time when the grade was awarded.
                            </p>
                        )}
                    </div>

                    {/* Notes */}
                    <div className="col-span-full">
                        <InputLabel htmlFor="notes">Notes</InputLabel>
                        <TextareaInput
                            id="notes"
                            name="notes"
                            value={data.notes}
                            className="mt-1 block w-full"
                            onChange={e => {
                                onChange('notes', e.target.value);
                            }}
                            placeholder="Notes"
                            rows={5}
                        />

                        <InputError message={errors.notes} className="mt-2" />
                    </div>
                </div>
            </div>
        </div>
    );
}
