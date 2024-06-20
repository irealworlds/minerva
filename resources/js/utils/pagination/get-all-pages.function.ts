import { PaginatedCollection } from '@/types/paginated-result.contract';
import axios from 'axios';

export async function fetchAllPages<T, TResponse>(
    uri: string,
    responseSelector: (response: TResponse) => PaginatedCollection<T>
): Promise<T[]> {
    const results: T[] = [];
    let lastPage = 1;

    for (let currentPage = 1; currentPage <= lastPage; currentPage++) {
        const response = await axios.get<TResponse>(uri);
        const paginatedCollection = responseSelector(response.data);

        results.push(...paginatedCollection.data);

        lastPage = paginatedCollection.last_page;
    }

    return results;
}
