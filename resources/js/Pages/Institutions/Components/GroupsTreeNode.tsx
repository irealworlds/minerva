import { StudentGroupTreeNodeViewModel } from '@/types/ViewModels/student-group-tree.view-model';
import React, { useContext, useMemo } from 'react';
import GroupsTree from '@/Pages/Institutions/Components/GroupsTree';
import { ChevronRightIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { InstitutionsTreeContext } from '@/Pages/Institutions/Partials/Tabs/ManageInstitutionGroups';
import { PlusCircleIcon } from '@heroicons/react/24/outline';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { Link } from '@inertiajs/react';
import { AuthenticatedContext } from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Permission } from '@/types/permission.enum';

export default function GroupsTreeNode({
  node,
}: {
  node: StudentGroupTreeNodeViewModel;
}) {
  const { expandedNodeIds, setExpandedNodeIds } = useContext(
    InstitutionsTreeContext
  );
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
              'size-5 transition-all duration-250',
              hasChildren ? '' : 'opacity-0',
              hasChildren && expanded ? 'rotate-90' : ''
            )}
          />
          {node.name}
        </button>

        {/* Creation button for non-hover devices */}
        {hasPermissions(Permission.StudentGroupCreate) && (
          <Link href={creationUri}>
            <PrimaryButton className="block can-hover:hidden justify-center w-full">
              Add
            </PrimaryButton>
          </Link>
        )}
      </div>

      {/* Creation button for hover-enabled devices*/}
      {hasPermissions(Permission.StudentGroupCreate) && (
        <Link
          href={creationUri}
          className="hidden can-hover:flex pl-10 text-sm items-center opacity-0 hover:opacity-100 ">
          <PlusCircleIcon className="size-6 z-10" />
          <div className="w-full h-0.5 bg-current -ml-1" />
        </Link>
      )}

      {hasChildren && expanded && (
        <GroupsTree className="pl-2 md:pl-10" tree={node.children} />
      )}
    </li>
  );
}
