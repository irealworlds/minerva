import { PlusIcon } from '@heroicons/react/20/solid';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { AcademicCapIcon } from '@heroicons/react/24/outline';
import { Link } from '@inertiajs/react';

export default function NoEducators() {
    return (
        <div className="text-center">
            <AcademicCapIcon className="mx-auto size-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900">
                No educators
            </h3>
            <p className="mt-1 text-sm text-gray-500">
                No educators could be found in the system matching your search.
            </p>
            <div className="mt-6">
                <Link href={route('admin.educators.create')}>
                    <PrimaryButton type="button">
                        <PlusIcon
                            className="-ml-0.5 mr-1.5 h-5 w-5"
                            aria-hidden="true"
                        />
                        New educator
                    </PrimaryButton>
                </Link>
            </div>
        </div>
    );
}
