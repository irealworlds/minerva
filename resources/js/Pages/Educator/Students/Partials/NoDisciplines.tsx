import { AcademicCapIcon, ArrowLeftIcon } from '@heroicons/react/24/outline';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { Link } from '@inertiajs/react';

export default function NoDisciplines() {
    return (
        <div className="text-center">
            <AcademicCapIcon className="mx-auto size-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900 text-center">
                No discipline
            </h3>
            <p className="mt-1 text-sm text-gray-500">
                You are not teaching any discipline for this student.
            </p>
            <div className="mt-6">
                <Link href={route('educators.studentGroups.list')}>
                    <PrimaryButton type="button">
                        <ArrowLeftIcon
                            className="-ml-0.5 mr-1.5 h-5 w-5"
                            aria-hidden="true"
                        />
                        Back to list
                    </PrimaryButton>
                </Link>
            </div>
        </div>
    );
}
