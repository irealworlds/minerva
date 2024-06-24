export interface StudentDisciplineEnrolmentViewModel {
    key: string;
    disciplineKey: string;
    disciplineName: string;
    disciplineAbbreviation: string | null;
    disciplinePictureUri: string;
    educatorKey: string;
    educatorName: string;
    educatorPictureUri: string;
    gradesCount: number;
    averageGrade: number | null;
}
