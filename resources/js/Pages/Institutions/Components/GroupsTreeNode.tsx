import { StudentGroupTreeNodeViewModel } from '@/types/ViewModels/student-group-tree.view-model';
import React, { useContext, useMemo } from 'react';
import GroupsTree from '@/Pages/Institutions/Components/GroupsTree';
import { ChevronRightIcon } from '@heroicons/react/20/solid';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { InstitutionsTreeContext } from '@/Pages/Institutions/Partials/Tabs/ManageInstitutionGroups';
import { PlusCircleIcon } from '@heroicons/react/24/outline';
import PrimaryButton from '@/Components/PrimaryButton';
import { Link } from '@inertiajs/react';

export default function GroupsTreeNode({
  node,
}: {
  node: StudentGroupTreeNodeViewModel;
}) {
  const { expandedNodeIds, setExpandedNodeIds } = useContext(
    InstitutionsTreeContext
  );
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

  return (
    <li>
      <button
        onClick={() => {
          toggleExpanded();
        }}
        disabled={!hasChildren}
        className={combineClassNames(
          'p-4 flex flex-col items-stretch md:flex-row md:justify-between md:items-center gap-2 text-left w-full rounded-lg transition-colors group',
          hasChildren ? 'hover:bg-gray-100' : ''
        )}>
        <div className="flex items-center gap-2">
          <ChevronRightIcon
            className={combineClassNames(
              'size-5 transition-all duration-250',
              hasChildren ? '' : 'opacity-0',
              hasChildren && expanded ? 'rotate-90' : ''
            )}
          />
          {node.name}
        </div>
        <Link href="#">
          <PrimaryButton className="block can-hover:hidden justify-center">
            Add
          </PrimaryButton>
        </Link>
      </button>
      <Link
        href="#"
        className="hidden can-hover:flex pl-10 text-sm items-center opacity-0 hover:opacity-100 ">
        <PlusCircleIcon className="size-6 z-10" />
        <div className="w-full h-0.5 bg-current -ml-1" />
      </Link>
      {hasChildren && expanded && (
        <GroupsTree className="pl-2 md:pl-10" tree={node.children} />
      )}
    </li>
  );
}
