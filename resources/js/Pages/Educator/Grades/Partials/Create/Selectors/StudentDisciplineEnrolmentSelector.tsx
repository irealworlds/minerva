import { UserCircleIcon, UserIcon } from '@heroicons/react/24/outline';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { StudentDisciplineEnrolmentDto } from '@/types/dtos/educator/student-discipline-enrolment.dto';
import { useEffect, useMemo, useState } from 'react';
import { fetchAllPages } from '@/utils/pagination/get-all-pages.function';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    Radio,
    RadioGroup,
} from '@headlessui/react';
import TextInput from '@/Components/Forms/Controls/TextInput';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';

interface StudentDisciplineEnrolmentSelectorProps {
    className?: string;
    disabled?: boolean;

    value: StudentDisciplineEnrolmentDto | null;
    onChange: (value: StudentDisciplineEnrolmentDto | null) => void;

    disciplineKey: string;
    studentGroupKey: string;
}

export default function StudentDisciplineEnrolmentSelector({
    className,
    disabled,

    value,
    onChange,

    disciplineKey,
    studentGroupKey,
}: StudentDisciplineEnrolmentSelectorProps) {
    const [loadedEnrolments, setLoadedEnrolments] = useState<
        StudentDisciplineEnrolmentDto[]
    >([]);
    const [open, setOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const [selected, setSelected] =
        useState<StudentDisciplineEnrolmentDto | null>(value);
    const [searchQuery, setSearchQuery] = useState('');

    const filteredEnrolments = useMemo(() => {
        return loadedEnrolments.filter(enrolment => {
            if (searchQuery.trim().length > 0) {
                return enrolment.studentName
                    .toLowerCase()
                    .includes(searchQuery.toLowerCase());
            }

            return true;
        });
    }, [loadedEnrolments, searchQuery]);

    useEffect(() => {
        const searchQuery: Record<string, string> = {};

        if (disciplineKey) {
            searchQuery.disciplineKey = disciplineKey;
        }

        if (studentGroupKey) {
            searchQuery.studentGroupKey = studentGroupKey;
        }

        setLoading(true);
        fetchAllPages(
            route(
                'api.educator.studentDisciplineEnrolments.index',
                searchQuery
            ),
            (response: PaginatedCollection<StudentDisciplineEnrolmentDto>) =>
                response
        )
            .then(
                enrolments => {
                    setLoadedEnrolments(enrolments);
                },
                () => {
                    // Do nothing
                }
            )
            .finally(() => {
                setLoading(false);
            });
    }, [disciplineKey, studentGroupKey]);

    function saveSelection() {
        if (!selected) {
            throw new Error('No selection has been made.');
        }

        onChange(selected);
    }

    return (
        <>
            <div
                className={combineClassNames(
                    className,
                    disabled && 'opacity-50'
                )}>
                <label
                    htmlFor="student-discipline-enrolment"
                    className="block text-sm font-medium leading-6 text-gray-900">
                    Student
                </label>
                <div className="mt-2 flex items-center gap-x-3">
                    {value ? (
                        <div className="flex items-center gap-x-3">
                            <img
                                src={value.studentPictureUri}
                                className="size-14 rounded-full"
                                aria-hidden="true"
                                alt={value.studentName}
                            />
                            <div className="mr-5">
                                <h3 className="font-medium">
                                    {value.studentName}
                                </h3>
                                <p className="text-gray-500 text-sm">
                                    {value.studentGroupName}
                                </p>
                            </div>
                        </div>
                    ) : (
                        <UserCircleIcon
                            className="size-14 text-gray-300"
                            aria-hidden="true"
                        />
                    )}
                    <PrimaryButton
                        type="button"
                        disabled={disabled}
                        onClick={() => {
                            setOpen(true);
                        }}>
                        Change
                    </PrimaryButton>
                </div>
            </div>

            <Dialog className="relative z-50" open={open} onClose={setOpen}>
                <div
                    className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in"
                    aria-hidden="true"
                />

                <div className="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <DialogPanel className="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-[closed]:translate-y-4 data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in sm:my-8 sm:w-full sm:max-w-4xl data-[closed]:sm:translate-y-0 data-[closed]:sm:scale-95">
                            <div className="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div className="sm:flex sm:items-start">
                                    <div className="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-800 sm:mx-0 sm:h-10 sm:w-10">
                                        <UserIcon
                                            className="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div className="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left grow">
                                        <DialogTitle
                                            as="h3"
                                            className="text-base font-semibold leading-6 text-gray-900">
                                            Select student
                                        </DialogTitle>
                                        <div className="mt-2">
                                            <p className="text-sm text-gray-500">
                                                Select the student you are
                                                awarding a grade to from the
                                                list of students you teach in
                                                this student group.
                                            </p>

                                            <TextInput
                                                className="mt-6 w-full max-w-xs"
                                                id="search-students"
                                                name="search-students"
                                                type="search"
                                                placeholder="Search students"
                                                value={searchQuery}
                                                onChange={event => {
                                                    setSearchQuery(
                                                        event.target.value
                                                    );
                                                }}
                                            />

                                            {loading ? (
                                                <div
                                                    aria-label="Student enrolments"
                                                    className="mt-4 animate-pulse">
                                                    <div className="relative -space-y-px rounded-md bg-white">
                                                        <div className="grid grid-cols-12 gap-12 border p-4 pl-4 pr-6">
                                                            <div className="h-4 bg-gray-200 rounded col-span-6"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                        </div>
                                                        <div className="grid grid-cols-12 gap-12 border p-4 pl-4 pr-6">
                                                            <div className="h-4 bg-gray-200 rounded col-span-6"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                        </div>
                                                        <div className="grid grid-cols-12 gap-12 border p-4 pl-4 pr-6">
                                                            <div className="h-4 bg-gray-200 rounded col-span-6"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                        </div>
                                                        <div className="grid grid-cols-12 gap-12 border p-4 pl-4 pr-6">
                                                            <div className="h-4 bg-gray-200 rounded col-span-6"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                            <div className="h-4 bg-gray-200 rounded col-span-3"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            ) : filteredEnrolments.length ===
                                              0 ? (
                                                <div className="mt-6">
                                                    <h3 className="mt-2 text-sm font-semibold text-gray-900">
                                                        No results
                                                    </h3>
                                                    <p className="mt-1 text-sm text-gray-500">
                                                        No results were found
                                                        for this search.
                                                    </p>
                                                </div>
                                            ) : (
                                                <fieldset
                                                    aria-label="Student enrolments"
                                                    className="mt-4">
                                                    <RadioGroup
                                                        value={selected}
                                                        onChange={setSelected}
                                                        className="relative -space-y-px rounded-md bg-white">
                                                        {filteredEnrolments.map(
                                                            (
                                                                enrolment,
                                                                enrolmentIdx
                                                            ) => (
                                                                <Radio
                                                                    key={
                                                                        enrolment.key
                                                                    }
                                                                    value={
                                                                        enrolment
                                                                    }
                                                                    aria-label={
                                                                        enrolment.studentName
                                                                    }
                                                                    aria-description={
                                                                        enrolment.studentName
                                                                    }
                                                                    className={({
                                                                        checked,
                                                                    }) =>
                                                                        combineClassNames(
                                                                            enrolmentIdx ===
                                                                                0
                                                                                ? 'rounded-tl-md rounded-tr-md'
                                                                                : '',
                                                                            enrolmentIdx ===
                                                                                filteredEnrolments.length -
                                                                                    1
                                                                                ? 'rounded-bl-md rounded-br-md'
                                                                                : '',
                                                                            checked
                                                                                ? 'z-10 border-indigo-200 bg-indigo-50'
                                                                                : 'border-gray-200',
                                                                            'relative flex cursor-pointer flex-col border p-4 focus:outline-none md:grid md:grid-cols-3 md:pl-4 md:pr-6'
                                                                        )
                                                                    }>
                                                                    {({
                                                                        focus,
                                                                        checked,
                                                                    }) => (
                                                                        <>
                                                                            <span className="flex items-center text-sm">
                                                                                <span
                                                                                    className={combineClassNames(
                                                                                        checked
                                                                                            ? 'border-transparent bg-indigo-600'
                                                                                            : 'border-gray-300 bg-white',
                                                                                        focus
                                                                                            ? 'ring-2 ring-indigo-600 ring-offset-2'
                                                                                            : '',
                                                                                        'flex h-4 w-4 items-center justify-center rounded-full border'
                                                                                    )}
                                                                                    aria-hidden="true">
                                                                                    <span className="h-1.5 w-1.5 rounded-full bg-white" />
                                                                                </span>
                                                                                <span
                                                                                    className={combineClassNames(
                                                                                        checked
                                                                                            ? 'text-indigo-900'
                                                                                            : 'text-gray-900',
                                                                                        'ml-3 font-medium'
                                                                                    )}>
                                                                                    {
                                                                                        enrolment.studentName
                                                                                    }
                                                                                </span>
                                                                            </span>
                                                                            <span className="ml-6 pl-1 text-sm md:ml-0 md:pl-0">
                                                                                <span
                                                                                    className={
                                                                                        checked
                                                                                            ? 'text-indigo-700'
                                                                                            : 'text-gray-500'
                                                                                    }>
                                                                                    {
                                                                                        enrolment.disciplineName
                                                                                    }
                                                                                </span>
                                                                            </span>
                                                                            <span
                                                                                className={combineClassNames(
                                                                                    checked
                                                                                        ? 'text-indigo-700'
                                                                                        : 'text-gray-500',
                                                                                    'ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right'
                                                                                )}>
                                                                                {
                                                                                    enrolment.studentGroupName
                                                                                }
                                                                            </span>
                                                                        </>
                                                                    )}
                                                                </Radio>
                                                            )
                                                        )}
                                                    </RadioGroup>
                                                </fieldset>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-4">
                                <PrimaryButton
                                    type="button"
                                    onClick={() => {
                                        saveSelection();
                                        setOpen(false);
                                    }}>
                                    Select
                                </PrimaryButton>
                                <SecondaryButton
                                    type="button"
                                    onClick={() => {
                                        setOpen(false);
                                    }}
                                    data-autofocus>
                                    Cancel
                                </SecondaryButton>
                            </div>
                        </DialogPanel>
                    </div>
                </div>
            </Dialog>
        </>
    );
}
