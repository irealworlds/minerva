export interface InstitutionDto {
    id: string;
    name: string;
    pictureUri: string | null;
    ancestors: {
        id: string;
        name: string;
    }[];
}
