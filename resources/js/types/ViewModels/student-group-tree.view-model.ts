import { StudentGroupViewModel } from '@/types/ViewModels/student-group.view-model';

export type StudentGroupTreeNodeViewModel = StudentGroupViewModel & {
  children: StudentGroupTreeViewModel;
};

export interface StudentGroupTreeViewModel {
  items: StudentGroupTreeNodeViewModel[];
}
