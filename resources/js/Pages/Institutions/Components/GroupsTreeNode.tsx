import { StudentGroupTreeNodeViewModel } from '@/types/view-models/student-group-tree.view-model';
import React, { useContext, useMemo } from 'react';
import GroupsTree from '@/Pages/Institutions/Components/GroupsTree';
import { ChevronRightIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { Link } from '@inertiajs/react';
import { AuthenticatedContext } from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Permission } from '@/types/permission.enum';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import { InstitutionGroupsManagementContext } from '@/Pages/Institutions/ManageInstitutionGroups';

export default function GroupsTreeNode({
    node,
}: {
    node: StudentGroupTreeNodeViewModel;
}) {
    const { expandedNodeIds, setExpandedNodeIds, openGroupManagement } =
        useContext(InstitutionGroupsManagementContext);
    const { hasPermissions } = useContext(AuthenticatedContext);
    const expanded = useMemo(
        () => expandedNodeIds.has(node.id),
        [expandedNodeIds]
    );
    const hasChildren = useMemo(() => node.children.items.length > 0, [node]);
    function toggleExpanded(): void {
        const list = new Set(expandedNodeIds);
        if (list.has(node.id)) {
            list.delete(node.id);
        } else {
            list.add(node.id);
        }
        setExpandedNodeIds(list);
    }

    const creationUri = route('student_groups.create', {
        parentType: 'studentGroup',
        parentId: node.id,
    });

    return (
        <li>
            <div className="group">
                {/* Children toggler */}
                <button
                    onClick={() => {
                        toggleExpanded();
                    }}
                    disabled={!hasChildren}
                    className={combineClassNames(
                        'p-4 flex items-center gap-2 text-left w-full rounded-lg transition-colors',
                        hasChildren ? 'hover:bg-gray-100' : ''
                    )}>
                    <ChevronRightIcon
                        className={combineClassNames(
                            'size-5 transition-all duration-250 hidden can-hover:block',
                            hasChildren ? '' : 'opacity-0',
                            hasChildren && expanded ? 'rotate-90' : ''
                        )}
                    />
                    {node.name}
                </button>

                {/* Creation button for non-hover devices */}
                <div className="can-hover:hidden flex items-center flex-wrap gap-4 px-2 md:px-10 mt-1">
                    <SecondaryButton
                        disabled={!hasChildren}
                        onClick={() => {
                            toggleExpanded();
                        }}
                        className="justify-center grow">
                        View children
                    </SecondaryButton>
                    {hasPermissions(Permission.StudentGroupCreate) && (
                        <Link href={creationUri} className={'grow'}>
                            <SecondaryButton className="justify-center w-full">
                                Add child
                            </SecondaryButton>
                        </Link>
                    )}
                    {hasPermissions(Permission.StudentGroupCreate) && (
                        <PrimaryButton
                            className="justify-center grow"
                            onClick={() => {
                                openGroupManagement(node);
                            }}>
                            Manage
                        </PrimaryButton>
                    )}
                </div>

                {/* Creation button for hover-enabled devices*/}
                <div className="hidden can-hover:flex pl-10 text-sm items-center opacity-0 hover:opacity-100 gap-3">
                    {/* Manage button */}
                    <button
                        type="button"
                        onClick={() => {
                            openGroupManagement(node);
                        }}
                        className="bg-gray-800 dark:bg-gray-200 text-white text-xs px-3 py-1 rounded-full hover:bg-gray-700 dark:hover:bg-white">
                        Manage
                    </button>

                    {/* Add Child button */}
                    {hasPermissions(Permission.StudentGroupCreate) && (
                        <Link
                            href={creationUri}
                            className="flex grow items-center group">
                            {/* Button */}
                            <button
                                type="button"
                                className="bg-gray-800 dark:bg-gray-200 text-white text-xs px-3 py-1 rounded-full group-hover:bg-gray-700 dark:group-hover:bg-white whitespace-nowrap">
                                Add child
                            </button>
                            {/* Post line */}
                            <div
                                className={combineClassNames(
                                    'w-full h-0.5 bg-gray-800 dark:bg-gray-200 group-hover:bg-gray-700 dark:group-hover:bg-white -ml-1 relative',
                                    "before:content-[''] before:border-8 before:border-transparent before:border-l-gray-800 dark:before:border-l--gray-200 before:group-hover:border-l--gray-700 before:dark:group-hover:border-l-white before:absolute before:left-0 before:topa-0 before:-translate-y-1/2 before:w-0"
                                )}
                            />
                        </Link>
                    )}
                </div>
            </div>

            {hasChildren && expanded && (
                <GroupsTree className="pl-2 md:pl-10" tree={node.children} />
            )}
        </li>
    );
}
