import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { StudentGroupTreeViewModel } from '@/types/view-models/student-group-tree.view-model';
import { router } from '@inertiajs/react';
import { createContext, useState } from 'react';
import FilteredGroupsList from '@/Pages/Institutions/Partials/FilteredGroupsList';
import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';
import StudentGroupDetailsOverlay from '@/Pages/Institutions/Partials/StudentGroupDetailsOverlay';

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
    groups,
}: {
    user: AuthenticatedUserViewModel;
    institution: InstitutionViewModel;
    groups: StudentGroupTreeViewModel | null;
}) {
    if (!groups) {
        router.reload({
            only: ['groups'],
        });
    }

    const [expandedNodeIds, setExpandedNodeIds] = useState(new Set<string>());
    const [selectedGroup, setSelectedGroup] =
        useState<StudentGroupViewModel | null>(null);
    const [open, setOpen] = useState(false);

    function openGroupManagement(group: StudentGroupViewModel) {
        setSelectedGroup(group);
        setOpen(true);
    }

    return (
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
                <h2 className="text-base font-semibold leading-7 text-gray-900">
                    Group structure
                </h2>
                <p className="mt-1 text-sm leading-6 text-gray-600">
                    This institution's internal structure, expressed as student
                    groups.
                </p>

                <div className="mt-10">
                    <FilteredGroupsList initialGroups={groups} />
                </div>
            </div>
        </InstitutionGroupsManagementContext.Provider>
    );
}
