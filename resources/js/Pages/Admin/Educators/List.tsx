import { PageProps } from '@/types';
import AuthenticatedLayout from '@/Layouts/Authenticated/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { PlusIcon } from '@heroicons/react/20/solid';
import { PaginatedCollection } from '@/types/paginated-result.contract';
import { EducatorViewModel } from '@/types/view-models/admin/educator.view-model';
import NoEducators from '@/Pages/Admin/Educators/Partials/List/NoEducators';
import EducatorsList from '@/Pages/Admin/Educators/Partials/List/EducatorsList';
import Paginator from '@/Components/Paginator';

type ListPageProps = PageProps<{
    educators: PaginatedCollection<EducatorViewModel>;
}>;

export default function List({ auth, educators }: ListPageProps) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Educators list" />

            <div className="border-b border-gray-200 pb-5">
                <div className="sm:flex sm:items-baseline sm:justify-between">
                    <div className="sm:w-0 sm:flex-1">
                        <h1
                            id="message-heading"
                            className="text-base font-semibold leading-6 text-gray-900">
                            Educators list
                        </h1>
                        <p className="mt-1 truncate text-sm text-gray-500">
                            A list of educators that are currently registered in
                            the system.
                        </p>
                    </div>

                    <div className="mt-4 flex items-center justify-between sm:ml-6 sm:mt-0 sm:flex-shrink-0 sm:justify-start">
                        <Link href={route('admin.educators.create')}>
                            <PrimaryButton type="button">
                                <PlusIcon className="size-4 mr-2" />
                                New educator
                            </PrimaryButton>
                        </Link>
                    </div>
                </div>
            </div>

            <div className="mt-6">
                {educators.total === 0 ? (
                    <NoEducators />
                ) : (
                    <>
                        <EducatorsList educators={educators.data} />
                        <Paginator className="mt-6" collection={educators} />
                    </>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
