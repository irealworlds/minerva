export interface StudentGroupViewModel {
  id: string;
  name: string;
  ancestors: {
    id: string;
    type: 'institution' | 'studentGroup';
    name: string;
  }[];
}
