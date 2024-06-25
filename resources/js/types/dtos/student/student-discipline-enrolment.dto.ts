export interface StudentDisciplineEnrolmentDto {
    key: string;
    disciplineKey: string;
    disciplineName: string;
    disciplineAbbreviation: string | null;
    studentGroupKey: string;
    studentGroupName: string;
    studentKey: string;
    studentName: string;
    studentPictureUri: string;
}
