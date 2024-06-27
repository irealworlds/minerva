export interface StudentDisciplineEnrolmentViewModel {
    enrolmentKeys: string[];

    disciplineKey: string;
    disciplineName: string;
    disciplineAbbreviation: string | null;
    disciplinePictureUri: string;

    educators: {
        key: string;
        name: string;
        pictureUri: string;
    }[];

    gradesCount: number;
    averageGrade: number | null;
}
