import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { StudentGroupTreeViewModel } from '@/types/view-models/student-group-tree.view-model';
import { Head, Link } from '@inertiajs/react';
import React, { createContext, useEffect, useMemo, useState } from 'react';
import FilteredGroupsList from '@/Pages/Admin/Institutions/Partials/FilteredGroupsList';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import StudentGroupDetailsOverlay from '@/Pages/Admin/Institutions/Partials/Manage/Groups/StudentGroupDetailsOverlay';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import ManageInstitutionLayout from '@/Pages/Admin/Institutions/Partials/ManageInstitutionLayout';

export const InstitutionGroupsManagementContext = createContext<{
    expandedNodeIds: Set<string>;
    setExpandedNodeIds: (newValue: Set<string>) => void;
    openGroupManagement: (group: StudentGroupViewModel) => void;
}>({
    expandedNodeIds: new Set<string>(),
    setExpandedNodeIds: () => {
        // Do nothing
    },
    openGroupManagement: () => {
        // Do nothing
    },
});

export default function ManageInstitutionGroups({
    auth,
    groups,
    institution,
}: PageProps<{
    institution: InstitutionViewModel;
    groups: StudentGroupTreeViewModel;
}>) {
    const [expandedNodeIds, setExpandedNodeIds] = useState(new Set<string>());
    const [selectedGroupId, setSelectedGroupId] = useState<string | null>(null);
    const [open, setOpen] = useState(false);

    function openGroupManagement(group: StudentGroupViewModel) {
        setSelectedGroupId(group.id);
        setOpen(true);
    }

    // Get the selected group's details
    const selectedGroup = useMemo<StudentGroupViewModel | null>(() => {
        function findGroupByIdInTree(
            tree: StudentGroupTreeViewModel
        ): StudentGroupViewModel | null {
            const foundOnThisLevel = tree.items.find(
                item => item.id === selectedGroupId
            );

            if (foundOnThisLevel) {
                return foundOnThisLevel;
            }

            for (const child of tree.items) {
                const foundInChildren = findGroupByIdInTree(child.children);
                if (foundInChildren) {
                    return foundInChildren;
                }
            }

            return null;
        }

        return findGroupByIdInTree(groups);
    }, [groups, selectedGroupId]);

    // Close the overlay if the selected group is removed
    useEffect(() => {
        if (!selectedGroup) {
            setOpen(false);
        }
    }, [selectedGroup]);

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Institution groups management" />

            <ManageInstitutionLayout institution={institution}>
                <InstitutionGroupsManagementContext.Provider
                    value={{
                        expandedNodeIds,
                        setExpandedNodeIds,
                        openGroupManagement,
                    }}>
                    <StudentGroupDetailsOverlay
                        open={open}
                        onClose={() => {
                            setOpen(false);
                        }}
                        group={selectedGroup}
                    />
                    <div className="bg-white p-6 rounded-lg shadow">
                        <div className="flex items-center justify-between">
                            <div className="grow">
                                <h2 className="text-base font-semibold leading-7 text-gray-900">
                                    Group structure
                                </h2>
                                <p className="mt-1 text-sm leading-6 text-gray-600">
                                    This institution's internal structure,
                                    expressed as student groups.
                                </p>
                            </div>
                            <div>
                                <Link
                                    href={route('admin.student_groups.create', {
                                        parentType: 'institution',
                                        parentId: institution.id,
                                    })}>
                                    <PrimaryButton>Add group</PrimaryButton>
                                </Link>
                            </div>
                        </div>

                        <div className="mt-10">
                            <FilteredGroupsList initialGroups={groups} />
                        </div>
                    </div>
                </InstitutionGroupsManagementContext.Provider>
            </ManageInstitutionLayout>
        </AuthenticatedLayout>
    );
}
