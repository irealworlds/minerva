export interface StudentEnrolmentDetailsViewModel {
    id: string;

    studentRegistrationId: string;
    studentName: string;

    studentGroupKey: string;
    studentGroupName: string;
    studentGroupAncestors: { id: string; name: string }[];

    enroledDisciplineCount: number;
    studentGroupDisciplineCount: number;
    allEnrolmentsList: { id: string; name: string }[];

    parentInstitutionId: string;
    parentInstitutionName: string;
    parentInstitutionAncestors: { id: string; name: string }[];

    enroledAt: string;
}
