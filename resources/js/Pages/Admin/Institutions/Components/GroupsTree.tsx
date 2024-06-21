import { StudentGroupTreeViewModel } from '@/types/view-models/student-group-tree.view-model';
import { combineClassNames } from '@/utils/combine-class-names.function';
import GroupsTreeNode from '@/Pages/Admin/Institutions/Components/GroupsTreeNode';

interface GroupsTreeProps {
    className?: string;
    tree: StudentGroupTreeViewModel;
}

export default function GroupsTree({ tree, className }: GroupsTreeProps) {
    return (
        <ul
            className={combineClassNames(
                'space-y-2 pt-2 w-full border-l box-border',
                className
            )}>
            {tree.items.map(node => (
                <GroupsTreeNode key={node.id} node={node} />
            ))}
        </ul>
    );
}
