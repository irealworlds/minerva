import { PlusIcon } from '@heroicons/react/20/solid';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { Link } from '@inertiajs/react';
import { InstitutionViewModel } from '@/types/view-models/institution.view-model';

export default function NoDisciplines({
    institution,
}: {
    institution: InstitutionViewModel;
}) {
    return (
        <div className="text-center">
            <svg
                className="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true">
                <path
                    vectorEffect="non-scaling-stroke"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
                />
            </svg>
            <h3 className="mt-2 text-sm font-semibold text-gray-900">
                No disciplines
            </h3>
            <p className="mt-1 text-sm text-gray-500">
                Add a new existing discipline or create a new discipline.
            </p>
            <div className="mt-6">
                <Link
                    href={route('disciplines.create', {
                        institutions: institution.id,
                    })}>
                    <PrimaryButton type="button">
                        <PlusIcon
                            className="-ml-0.5 mr-1.5 size-5"
                            aria-hidden="true"
                        />
                        Create discipline
                    </PrimaryButton>
                </Link>
            </div>
        </div>
    );
}
