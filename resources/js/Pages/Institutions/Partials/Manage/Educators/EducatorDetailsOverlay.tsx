import {
    Dialog,
    DialogPanel,
    DialogTitle,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Transition,
    TransitionChild,
} from '@headlessui/react';
import { XMarkIcon } from '@heroicons/react/24/outline';
import { EllipsisVerticalIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { InstitutionEducatorViewModel } from '@/types/view-models/institution-educator.view-model';
import EducatorOverlayReadonlyDetails from '@/Pages/Institutions/Partials/Manage/Educators/EducatorOverlayReadonlyDetails';
import { createContext, useContext, useEffect, useState } from 'react';
import { InstitutionManagementContext } from '@/Pages/Institutions/Partials/ManageInstitutionLayout';
import { router } from '@inertiajs/react';
import Spinner from '@/Components/Spinner';
import EducatorOverlayAddDiscipline from '@/Pages/Institutions/Partials/Manage/Educators/EducatorOverlayAddDiscipline';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { EducatorTaughtDisciplineDto } from '@/types/dtos/educator-taught-discipline.dto';
import { fetchAllPages } from '@/utils/pagination/get-all-pages.function';
import EducatorOverlayAddRole from '@/Pages/Institutions/Partials/Manage/Educators/EducatorOverlayAddRole';

interface EducatorDetailsOverlayProps {
    open: boolean;
    educator: InstitutionEducatorViewModel | null;
    onClose: () => void;
}

export const EducatorManagementContext = createContext<{
    educator?: InstitutionEducatorViewModel;
}>({});

export default function EducatorDetailsOverlay({
    educator,
    open,
    onClose,
}: EducatorDetailsOverlayProps) {
    const [currentSection, setCurrentSection] = useState<
        'readonly' | 'add-discipline' | 'add-roles'
    >('readonly');
    const [deleting, setDeleting] = useState(false);
    const { institution } = useContext(InstitutionManagementContext);
    const [taughtDisciplines, setTaughtDisciplines] = useState<
        EducatorTaughtDisciplineDto[] | undefined
    >(undefined);

    useEffect(() => {
        setCurrentSection('readonly');
    }, [educator]);

    function renderCurrentSection() {
        if (!educator || !institution) {
            return <></>;
        }

        switch (currentSection) {
            case 'readonly':
                return (
                    <EducatorOverlayReadonlyDetails
                        educator={educator}
                        disciplines={taughtDisciplines}
                        setCurrentSection={setCurrentSection}
                    />
                );
            case 'add-discipline':
                return (
                    <EducatorOverlayAddDiscipline
                        educatorId={educator.id}
                        parentInstitutionId={institution.id}
                        setCurrentSection={setCurrentSection}
                    />
                );
            case 'add-roles':
                return (
                    <EducatorOverlayAddRole
                        educatorId={educator.id}
                        parentInstitutionId={institution.id}
                        setCurrentSection={setCurrentSection}
                    />
                );
            default:
                return <></>;
        }
    }

    function removeEducatorFromInstitution() {
        if (!institution) {
            throw new Error('Institution not found');
        }
        if (!educator) {
            throw new Error('Educator not found');
        }

        router.delete(
            route('institutions.educators.delete', {
                institution: institution.id,
                educator: educator.id,
            }),
            {
                onStart: () => {
                    setDeleting(true);
                },
                onFinish: () => {
                    setDeleting(false);
                },
            }
        );
    }

    async function refreshTaughtDisciplines() {
        if (!educator) {
            return;
        }

        setTaughtDisciplines(undefined);

        const disciplines = await fetchAllPages(
            route('api.educators.disciplines.index', {
                educator: educator.id,
            }),
            (response: PaginatedCollection<EducatorTaughtDisciplineDto>) =>
                response
        );

        setTaughtDisciplines(disciplines);
    }

    useEffect(() => {
        refreshTaughtDisciplines().then(
            () => {
                // Do nothing
            },
            () => {
                // Do nothing
            }
        );
    }, [educator]);

    return (
        <EducatorManagementContext.Provider
            value={{ educator: educator ?? undefined }}>
            <Transition show={open}>
                <Dialog
                    className="relative z-50"
                    onClose={() => {
                        onClose();
                    }}>
                    <TransitionChild
                        enter="ease-in-out duration-500"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in-out duration-500"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                    </TransitionChild>

                    <div className="fixed inset-0" />

                    <div className="fixed inset-0 overflow-hidden">
                        <div className="absolute inset-0 overflow-hidden">
                            <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                                <TransitionChild
                                    enter="transform transition ease-in-out duration-500 sm:duration-700"
                                    enterFrom="translate-x-full"
                                    enterTo="translate-x-0"
                                    leave="transform transition ease-in-out duration-500 sm:duration-700"
                                    leaveFrom="translate-x-0"
                                    leaveTo="translate-x-full">
                                    <DialogPanel className="pointer-events-auto w-screen max-w-2xl">
                                        <div className="flex h-full flex-col bg-white shadow-xl">
                                            <div className="px-4 py-6 sm:px-6">
                                                <div className="flex items-start justify-between">
                                                    <DialogTitle className="text-base font-semibold leading-6 text-gray-900">
                                                        Educator profile
                                                    </DialogTitle>
                                                    <div className="ml-3 flex h-7 items-center">
                                                        <button
                                                            type="button"
                                                            className="relative rounded-md bg-white text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-indigo-500"
                                                            onClick={() => {
                                                                onClose();
                                                            }}>
                                                            <span className="absolute -inset-2.5" />
                                                            <span className="sr-only">
                                                                Close panel
                                                            </span>
                                                            <XMarkIcon
                                                                className="h-6 w-6"
                                                                aria-hidden="true"
                                                            />
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            {/* Main */}
                                            <div className="grow divide-y divide-gray-200 overflow-y-auto">
                                                <div className="pb-6">
                                                    <div className="h-24 bg-indigo-700 sm:h-20 lg:h-28" />
                                                    <div className="-mt-12 flow-root px-4 sm:-mt-8 sm:flex sm:items-end sm:px-6 lg:-mt-16">
                                                        <div>
                                                            <div className="-m-1 flex">
                                                                <div className="inline-flex overflow-hidden rounded-lg border-4 border-white">
                                                                    {educator && (
                                                                        <img
                                                                            className="h-24 w-24 flex-shrink-0 sm:h-40 sm:w-40 lg:h-48 lg:w-48"
                                                                            src={
                                                                                educator.pictureUri
                                                                            }
                                                                            alt=""
                                                                        />
                                                                    )}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="mt-6 sm:ml-6 sm:flex-1">
                                                            <div>
                                                                <div className="flex items-center">
                                                                    <h3 className="text-xl font-bold text-gray-900 sm:text-2xl">
                                                                        {
                                                                            educator?.name
                                                                        }
                                                                    </h3>
                                                                </div>
                                                                <p className="text-sm text-gray-500">
                                                                    {
                                                                        educator?.email
                                                                    }
                                                                </p>
                                                            </div>
                                                            <div className="mt-5 flex flex-wrap space-y-3 sm:space-x-3 sm:space-y-0">
                                                                <div className="ml-3 inline-flex sm:ml-0">
                                                                    <Menu
                                                                        as="div"
                                                                        className="relative inline-block text-left">
                                                                        <MenuButton
                                                                            disabled={
                                                                                deleting
                                                                            }
                                                                            className="relative inline-flex items-center rounded-md bg-white p-2 text-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:pointer-events-none">
                                                                            <span className="absolute -inset-1" />
                                                                            <span className="sr-only">
                                                                                Open
                                                                                options
                                                                                menu
                                                                            </span>
                                                                            {deleting ? (
                                                                                <Spinner
                                                                                    className="size-5"
                                                                                    aria-hidden="true"
                                                                                />
                                                                            ) : (
                                                                                <EllipsisVerticalIcon
                                                                                    className="size-5"
                                                                                    aria-hidden="true"
                                                                                />
                                                                            )}
                                                                        </MenuButton>
                                                                        <Transition
                                                                            enter="transition ease-out duration-100"
                                                                            enterFrom="transform opacity-0 scale-95"
                                                                            enterTo="transform opacity-100 scale-100"
                                                                            leave="transition ease-in duration-75"
                                                                            leaveFrom="transform opacity-100 scale-100"
                                                                            leaveTo="transform opacity-0 scale-95">
                                                                            <MenuItems className="absolute left-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                                                <div className="py-1">
                                                                                    <MenuItem>
                                                                                        {({
                                                                                            focus,
                                                                                        }) => (
                                                                                            <button
                                                                                                type="button"
                                                                                                disabled={
                                                                                                    deleting
                                                                                                }
                                                                                                onClick={() => {
                                                                                                    removeEducatorFromInstitution();
                                                                                                }}
                                                                                                className={combineClassNames(
                                                                                                    focus
                                                                                                        ? 'bg-gray-100 text-gray-900'
                                                                                                        : 'text-gray-700',
                                                                                                    'block w-full px-4 py-2 text-sm'
                                                                                                )}>
                                                                                                Remove
                                                                                                from
                                                                                                institution
                                                                                            </button>
                                                                                        )}
                                                                                    </MenuItem>
                                                                                </div>
                                                                            </MenuItems>
                                                                        </Transition>
                                                                    </Menu>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="px-4 py-5 sm:px-0 sm:py-0">
                                                    {renderCurrentSection()}
                                                </div>
                                            </div>
                                        </div>
                                    </DialogPanel>
                                </TransitionChild>
                            </div>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </EducatorManagementContext.Provider>
    );
}
