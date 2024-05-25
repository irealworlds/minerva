export interface InstitutionViewModel {
  id: string;
  name: string;
  website: string | null;
  pictureUri: string | null;
  children: InstitutionViewModel[];
}
