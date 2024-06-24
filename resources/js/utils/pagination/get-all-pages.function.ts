import { PaginatedCollection } from '@/types/paginated-result.contract';
import axios from 'axios';

export async function fetchAllPages<T, TResponse>(
    uri: string,
    responseSelector: (response: TResponse) => PaginatedCollection<T>
): Promise<T[]> {
    const results: T[] = [];
    let lastPage = 1;

    const baseRequestUri = new URL(uri);

    for (let currentPage = 1; currentPage <= lastPage; currentPage++) {
        const pageUri = new URL(baseRequestUri);
        const queryParams = new URLSearchParams(pageUri.search);
        queryParams.set('page', currentPage.toString());
        pageUri.search = queryParams.toString();

        const response = await axios.get<TResponse>(pageUri.toString());
        const paginatedCollection = responseSelector(response.data);

        results.push(...paginatedCollection.data);

        lastPage = paginatedCollection.last_page;
    }

    return results;
}
