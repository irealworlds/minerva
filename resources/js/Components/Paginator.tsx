import { PaginatedCollection } from "@/types/paginated-result.contract";
import { Link } from "@inertiajs/react";
import SecondaryButton from "@/Components/SecondaryButton";

export default function Paginator<TItemType>({ collection } : { collection: PaginatedCollection<TItemType> }) {
    return (
        <nav
            className="flex items-center justify-between"
            aria-label="Pagination"
        >
            <div className="hidden sm:block">
                <p className="text-sm text-gray-700">
                    Showing <span className="font-medium">{collection.from}</span> to <span className="font-medium">{collection.to}</span> of <span className="font-medium">{collection.total}</span> results
                </p>
            </div>

            <div className="flex flex-1 justify-between sm:justify-end gap-2">
                <Link href={collection.prev_page_url!}>
                    <SecondaryButton type="button" disabled={collection.current_page <= 1}>
                        Previous
                    </SecondaryButton>
                </Link>
                <Link href={collection.next_page_url!}>
                    <SecondaryButton type="button" disabled={collection.current_page === collection.last_page}>
                        Next
                    </SecondaryButton>
                </Link>
            </div>
        </nav>
    )
}
