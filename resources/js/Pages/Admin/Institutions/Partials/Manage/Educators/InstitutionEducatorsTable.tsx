import { InstitutionEducatorViewModel } from '@/types/view-models/institution-educator.view-model';

interface InstitutionEducatorsTableProps {
    educators: InstitutionEducatorViewModel[];
    onEducatorSelected(id: string): void;
}
export default function InstitutionEducatorsTable({
    educators,
    onEducatorSelected,
}: InstitutionEducatorsTableProps) {
    return (
        <div className="mt-8 flow-root">
            <div className="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div className="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table className="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    E-mail address
                                </th>
                                <th
                                    scope="col"
                                    className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    className="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span className="sr-only">Manage</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {educators.map(educator => (
                                <tr key={educator.email}>
                                    <td className="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                        <div className="flex items-center">
                                            <div className="h-11 w-11 flex-shrink-0">
                                                <img
                                                    className="h-11 w-11 rounded-full"
                                                    src={educator.pictureUri}
                                                    alt=""
                                                />
                                            </div>
                                            <div className="ml-4">
                                                <div className="font-medium text-gray-900">
                                                    {educator.name}
                                                </div>
                                                <div className="mt-1 text-xs text-gray-500">
                                                    {educator.roles.join(', ')}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="whitespace-nowrap px-3 py-5 text-sm text-gray-900">
                                        <a href={`mailto:${educator.email}`}>
                                            {educator.email}
                                        </a>
                                    </td>
                                    <td className="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                        <span className="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Active
                                        </span>
                                    </td>
                                    <td className="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                        <button
                                            onClick={() => {
                                                onEducatorSelected(educator.id);
                                            }}
                                            type="button"
                                            className="text-indigo-600 hover:text-indigo-900">
                                            Manage
                                            <span className="sr-only">
                                                , {educator.email}
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
