import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import React from 'react';
import { SelectableEnrolmentDiscipline } from '@/Pages/Admin/StudentEnrolments/Create';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';

interface NewEnrolmentPreviewProps {
    onAdvance: () => void;
    onPreviousRequested?: () => void;
    disabled?: boolean;
    data: {
        newStudent: boolean;
        studentName: string | null;
        studentPictureUri: string;
        selectedInstitution: InstitutionViewModel | null;
        selectedStudentGroup: StudentGroupViewModel | null;
        selectedDisciplines: SelectableEnrolmentDiscipline[];
    };
}

export default function NewEnrolmentPreview({
    onAdvance,
    onPreviousRequested,
    disabled,
    data,
}: NewEnrolmentPreviewProps) {
    return (
        <div>
            <div className="px-4 sm:px-0">
                <h3 className="text-base font-semibold leading-7 text-gray-900">
                    Enrolment Preview
                </h3>
                <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Details about the student enrolment you are about to create.
                </p>
            </div>
            <div className="mt-6 border-t border-gray-100">
                <dl className="divide-y divide-gray-100">
                    {/* Student name */}
                    <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt className="text-sm font-medium leading-6 text-gray-900">
                            Full name
                        </dt>
                        <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {data.studentName?.trim().length ? (
                                <div className="flex items-center gap-2">
                                    {/*  Picture */}
                                    <img
                                        src={data.studentPictureUri}
                                        className="size-8 bg-gray-200 flex items-center justify-center rounded-full text-white shrink-0"
                                        aria-hidden="true"
                                        alt="picture"
                                    />

                                    {/* Text */}
                                    <div className="grow max-w-full">
                                        <h5 className="truncate">
                                            {data.studentName}
                                        </h5>
                                        <p className="text-gray-500"></p>
                                    </div>
                                </div>
                            ) : (
                                <span className="text-gray-500">N/A</span>
                            )}
                        </dd>
                    </div>

                    {/* Institution */}
                    <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt className="text-sm font-medium leading-6 text-gray-900">
                            Institution
                        </dt>
                        <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {data.selectedInstitution ? (
                                <div>
                                    <nav className="truncate text-gray-500">
                                        <ol className="flex items-center space-x-1">
                                            {data.selectedInstitution.ancestors.map(
                                                ancestor => (
                                                    <li
                                                        key={ancestor.id}
                                                        className="flex items-center">
                                                        <span className="mr-1 text-xs font-medium">
                                                            {ancestor.name}
                                                        </span>
                                                        <svg
                                                            className="size-3 flex-shrink-0"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                            aria-hidden="true">
                                                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                                        </svg>
                                                    </li>
                                                )
                                            )}
                                        </ol>
                                    </nav>
                                    {data.selectedInstitution.name}
                                </div>
                            ) : (
                                <span className="text-gray-500">N/A</span>
                            )}
                        </dd>
                    </div>

                    {/* Student group */}
                    <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt className="text-sm font-medium leading-6 text-gray-900">
                            Student group
                        </dt>
                        <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {data.selectedStudentGroup ? (
                                data.selectedStudentGroup.name
                            ) : (
                                <span className="text-gray-500">N/A</span>
                            )}
                        </dd>
                    </div>

                    {/* Disciplines */}
                    <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt className="text-sm font-medium leading-6 text-gray-900">
                            Studied disciplines
                        </dt>
                        <dd className="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {data.selectedDisciplines.length ? (
                                <ul
                                    role="list"
                                    className="divide-y divide-gray-100 rounded-md border border-gray-200">
                                    {data.selectedDisciplines.map(
                                        discipline => (
                                            <li
                                                key={discipline.id}
                                                className="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                <div className="flex w-0 flex-1 items-center">
                                                    <div>
                                                        <h5
                                                            className="truncate font-medium"
                                                            title={
                                                                discipline.disciplineName
                                                            }>
                                                            {
                                                                discipline.disciplineName
                                                            }
                                                        </h5>
                                                        <p className="text-gray-400 text-sm">
                                                            taught by{' '}
                                                            {
                                                                discipline.educatorName
                                                            }
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        )
                                    )}
                                </ul>
                            ) : (
                                <span className="text-gray-500">N/A</span>
                            )}
                        </dd>
                    </div>
                </dl>
            </div>

            {/* Actions */}
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
                    Save enrolment
                </PrimaryButton>
            </div>
        </div>
    );
}
