import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import React from 'react';
import EnrolmentCard from '@/Pages/Student/Enrolments/Partials/List/EnrolmentCard';

interface EnrolmentsListProps {
    enrolments: StudentGroupEnrolmentViewModel[];
}

export default function EnrolmentsList({ enrolments }: EnrolmentsListProps) {
    return (
        <ul
            role="list"
            className="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-3 xl:gap-x-8">
            {enrolments.map(enrolment => (
                <li key={enrolment.key} className="relative">
                    <EnrolmentCard enrolment={enrolment} />
                </li>
            ))}
        </ul>
    );
}
