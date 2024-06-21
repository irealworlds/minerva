import { SquaresPlusIcon } from '@heroicons/react/24/outline';

export default function NoGroups() {
    return (
        <div className="flex items-center justify-center">
            <button
                type="button"
                className="relative block w-full max-w-lg rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <SquaresPlusIcon className="mx-auto size-12 text-gray-400" />
                <h5 className="mt-2 font-semibold text-gray-900">No results</h5>
                <p className="mt-1 block text-sm text-gray-600">
                    Create a new student group
                </p>
            </button>
        </div>
    );
}
