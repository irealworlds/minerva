import React from 'react';
import { StudentGroupEnrolmentViewModel } from '@/types/view-models/student/student-group-enrolment.view-model';
import { Link } from '@inertiajs/react';

interface EnrolmentCardProps {
    enrolment: StudentGroupEnrolmentViewModel;
}

export default function EnrolmentCard({ enrolment }: EnrolmentCardProps) {
    return (
        <Link
            href={route('student.enrolments.read.overview', {
                enrolment: enrolment.key,
            })}>
            <div className="group aspect-video flex items-center justify-center w-full overflow-hidden rounded-lg bg-white shadow focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
                <img
                    src={enrolment.institutionPictureUri}
                    alt=""
                    className="pointer-events-none object-cover group-hover:opacity-75 transition-opacity"
                />
                <button
                    type="button"
                    className="absolute inset-0 focus:outline-none">
                    <span className="sr-only">
                        View details for your enrolment in{' '}
                        {enrolment.studentGroupName}
                    </span>
                </button>
            </div>
            <div className="mt-2">
                <p className="pointer-events-none block text-sm font-medium text-gray-500">
                    {enrolment.institutionName}
                </p>
                <p className="pointer-events-none block truncate font-medium text-gray-900">
                    {enrolment.studentGroupName}
                </p>
            </div>
        </Link>
    );
}
