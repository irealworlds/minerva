import { InstitutionEducatorViewModel } from '@/types/view-models/institution-educator.view-model';
import EducatorDetailsAddRole from '@/Pages/Institutions/Partials/Manage/Educators/EducatorDetailsAddRole';
import EducatorDetailsRoleEntry from '@/Pages/Institutions/Partials/Manage/Educators/EducatorDetailsRoleEntry';

interface EducatorOverlayReadonlyDetailsProps {
    educator: InstitutionEducatorViewModel;
}

export default function EducatorOverlayReadonlyDetails({
    educator,
}: EducatorOverlayReadonlyDetailsProps) {
    return (
        <dl className="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    E-mail address
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                    {educator.email}
                </dd>
            </div>
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    Roles
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0 grow">
                    <ul role="list" className="divide-y divide-gray-200">
                        {educator.roles.map((role, roleIdx) => (
                            <EducatorDetailsRoleEntry
                                key={roleIdx}
                                role={role}
                                index={roleIdx + 1}
                            />
                        ))}
                        <li className="py-2">
                            <EducatorDetailsAddRole />
                        </li>
                    </ul>
                </dd>
            </div>
            <div className="sm:flex sm:px-6 sm:py-5">
                <dt className="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                    Joined
                </dt>
                <dd className="mt-1 text-sm text-gray-900 sm:col-span-2 sm:ml-6 sm:mt-0">
                    <time dateTime={new Date(educator.createdAt).toISOString()}>
                        {new Date(educator.createdAt).toLocaleDateString(
                            undefined,
                            {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                            }
                        )}
                    </time>
                </dd>
            </div>
        </dl>
    );
}
