import { PaginatedCollection } from '@/types/paginated-result.contract';
import { Link } from '@inertiajs/react';
import SecondaryButton from '@/Components/Buttons/SecondaryButton';
import { combineClassNames } from '@/utils/combine-class-names.function';

export default function Paginator<TItemType>({
    collection,
    className,
}: {
    collection: PaginatedCollection<TItemType>;
    className?: string;
}) {
    return (
        <nav
            className={combineClassNames(
                'flex items-center justify-between',
                className
            )}
            aria-label="Pagination">
            {/* Current results
             */}
            <div className="hidden sm:block">
                <p className="text-sm text-gray-700">
                    Showing{' '}
                    <span className="font-medium">{collection.from}</span> to{' '}
                    <span className="font-medium">{collection.to}</span> of{' '}
                    <span className="font-medium">{collection.total}</span>{' '}
                    results
                </p>
            </div>

            <div className="flex flex-1 justify-between sm:justify-end gap-2">
                {/* Previous page button */}
                {collection.prev_page_url ? (
                    <Link href={collection.prev_page_url}>
                        <SecondaryButton
                            type="button"
                            disabled={collection.current_page <= 1}>
                            Previous
                        </SecondaryButton>
                    </Link>
                ) : (
                    <SecondaryButton
                        type="button"
                        disabled={collection.current_page <= 1}>
                        Previous
                    </SecondaryButton>
                )}

                {/* Next page button */}
                {collection.next_page_url ? (
                    <Link href={collection.next_page_url}>
                        <SecondaryButton
                            type="button"
                            disabled={
                                collection.current_page === collection.last_page
                            }>
                            Next
                        </SecondaryButton>
                    </Link>
                ) : (
                    <SecondaryButton
                        type="button"
                        disabled={
                            collection.current_page === collection.last_page
                        }>
                        Next
                    </SecondaryButton>
                )}
            </div>
        </nav>
    );
}
