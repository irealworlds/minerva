export interface StudentGroupDisciplineDto {
    id: string;
    name: string;
    abbreviation: string;
    educators: {
        educatorId: string;
        educatorName: string;
    }[];
}
