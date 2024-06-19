export interface InstitutionViewModel {
    id: string;
    name: string;
    website: string | null;
    pictureUri: string | null;
    ancestors: {
        id: string;
        name: string;
    }[];
    educatorsCount: number;
    studentsCount: number;
    disciplinesCount: number;
    childInstitutions: {
        id: string;
        name: string;
    }[];
    createdAt: string;
    updatedAt: string;
}
