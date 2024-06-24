export interface EducatorStudentGroupViewModel {
    id: string;
    name: string;
    institution: {
        id: string;
        name: string;
    };
    ancestors: {
        id: string;
        name: string;
    }[];
    disciplinesCount: number;
    studentsCount: number;
    teachingSince: string;
}
