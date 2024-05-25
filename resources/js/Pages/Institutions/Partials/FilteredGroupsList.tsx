import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import GroupsTree from '@/Pages/Institutions/Components/GroupsTree';
import React, { useContext, useMemo, useState } from 'react';
import {
  StudentGroupTreeNodeViewModel,
  StudentGroupTreeViewModel,
} from '@/types/ViewModels/student-group-tree.view-model';
import { InstitutionsTreeContext } from '@/Pages/Institutions/Partials/Tabs/ManageInstitutionGroups';
import NoGroups from '@/Pages/Institutions/Partials/NoGroups';

function countItems(filteredGroups: StudentGroupTreeViewModel): number {
  return (
    filteredGroups.items.length +
    filteredGroups.items
      .map(item => countItems(item.children))
      .reduce((accumulator, current) => accumulator + current, 0)
  );
}

// Function to filter the tree by a query
function filterStudentGroupTree(
  tree: StudentGroupTreeViewModel,
  query: string
): StudentGroupTreeViewModel {
  const filteredItems = tree.items
    .map(node => filterNode(node, query))
    .filter(node => node !== null);

  return { items: filteredItems as StudentGroupTreeNodeViewModel[] };
}

function filterNode(
  node: StudentGroupTreeNodeViewModel,
  query: string
): StudentGroupTreeNodeViewModel | null {
  // Recursively filter the children
  const filteredChildren = filterStudentGroupTree(node.children, query);

  // Check if the current node matches the query
  const matchesQuery = node.name.toLowerCase().includes(query.toLowerCase());

  // If the node matches the query or any of its children match, include it in the result
  if (matchesQuery || filteredChildren.items.length > 0) {
    return {
      ...node,
      children: filteredChildren,
    };
  }

  // If neither the node nor its children match, exclude it from the result
  return null;
}

function getAllIdsFromTree(tree: StudentGroupTreeViewModel): string[] {
  const ids: string[] = [];

  function collectIds(node: StudentGroupTreeNodeViewModel) {
    ids.push(node.id);
    for (const child of node.children.items) {
      collectIds(child);
    }
  }

  for (const item of tree.items) {
    collectIds(item);
  }

  return ids;
}

interface FilteredGroupsListProps {
  initialGroups: StudentGroupTreeViewModel | null;
}

export default function FilteredGroupsList({
  initialGroups,
}: FilteredGroupsListProps) {
  const [searchQuery, setSearchQuery] = useState('');
  const { setExpandedNodeIds } = useContext(InstitutionsTreeContext);

  const filteredGroups = useMemo<typeof initialGroups>(() => {
    if (!initialGroups) {
      return initialGroups;
    }
    if (searchQuery.length === 0) {
      setExpandedNodeIds(new Set());
      return initialGroups;
    } else {
      const filteredResults = filterStudentGroupTree(
        initialGroups,
        searchQuery
      );
      setExpandedNodeIds(new Set(getAllIdsFromTree(filteredResults)));
      return filteredResults;
    }
  }, [initialGroups, searchQuery]);

  const resultsCount = useMemo<number>(() => {
    if (!filteredGroups) return 0;
    return countItems(filteredGroups);
  }, [filteredGroups]);

  return (
    <>
      {initialGroups !== null && (
        <div className="mb-6">
          <InputLabel htmlFor="search" value="Search" />

          <TextInput
            id="search"
            type="search"
            name="search"
            value={searchQuery}
            className="mt-1 block w-full"
            placeholder="Search for a group"
            onChange={e => {
              setSearchQuery(e.target.value);
            }}
          />
          {filteredGroups && (
            <p className="text-right text-sm text-gray-500 mt-1">
              Found {resultsCount} results
            </p>
          )}
        </div>
      )}

      {filteredGroups?.items.length ? (
        <GroupsTree className="!border-0" tree={filteredGroups} />
      ) : (
        <NoGroups />
      )}
    </>
  );
}
