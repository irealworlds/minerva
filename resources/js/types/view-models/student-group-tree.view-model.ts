import { StudentGroupViewModel } from '@/types/view-models/student-group.view-model';

export type StudentGroupTreeNodeViewModel = StudentGroupViewModel & {
    children: StudentGroupTreeViewModel;
};

export interface StudentGroupTreeViewModel {
    items: StudentGroupTreeNodeViewModel[];
}
