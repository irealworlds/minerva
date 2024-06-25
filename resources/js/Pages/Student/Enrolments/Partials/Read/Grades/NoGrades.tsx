import { FolderIcon } from '@heroicons/react/24/outline';

export default function NoGrades() {
    return (
        <div className="text-center">
            <FolderIcon className="mx-auto h-12 w-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-semibold text-gray-900">
                No grades awarded
            </h3>
            <p className="mt-1 text-sm text-gray-500">
                You have been awarded no grades in this student group.
            </p>
        </div>
    );
}
