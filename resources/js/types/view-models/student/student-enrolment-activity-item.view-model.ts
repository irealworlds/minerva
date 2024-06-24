import { StudentEnrolmentActivityType } from '@/types/enums/student-enrolment-activity-type.enum';

export interface StudentEnrolmentActivityItemViewModel {
    type: StudentEnrolmentActivityType;
    properties: Record<string, unknown>;
    date: string;
}
