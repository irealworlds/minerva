import { StudentGroupDisciplineViewModel } from '@/types/view-models/student-group-discipline.view-model';

export interface StudentGroupViewModel {
    id: string;
    name: string;
    ancestors: {
        id: string;
        type: 'institution' | 'studentGroup';
        name: string;
    }[];
    disciplines: StudentGroupDisciplineViewModel[];
    childrenIds: string[];
    createdAt: string;
    updatedAt: string;
}
