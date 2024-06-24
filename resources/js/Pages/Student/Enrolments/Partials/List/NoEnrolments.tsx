import { MagnifyingGlassIcon } from '@heroicons/react/20/solid';
import { EnvelopeIcon, UserIcon } from '@heroicons/react/24/outline';
import { combineClassNames } from '@/utils/combine-class-names.function';

const items = [
    {
        name: 'Check your credentials',
        description:
            'Make sure you have signed into your student account with your credentials.',
        iconColor: 'bg-yellow-500',
        icon: UserIcon,
    },
    {
        name: 'Refine search',
        description:
            'This could be because your search filters are too strict.',
        iconColor: 'bg-pink-500',
        icon: MagnifyingGlassIcon,
    },
    {
        name: 'Contact your institution',
        description:
            'If you know you are enroled in an institution, contact them to remedy your issue.',
        iconColor: 'bg-purple-500',
        icon: EnvelopeIcon,
    },
];
export default function NoEnrolments() {
    return (
        <div className="mx-auto max-w-lg">
            <h2 className="text-base font-semibold leading-6 text-gray-900">
                No enrolments
            </h2>
            <p className="mt-1 text-sm text-gray-500">
                We could find no student group you are enroled in.
            </p>
            <ul
                role="list"
                className="mt-6 divide-y divide-gray-200 border-b border-t border-gray-200">
                {items.map((item, itemIdx) => (
                    <li key={itemIdx}>
                        <div className="group relative flex items-start space-x-3 py-4">
                            <div className="flex-shrink-0">
                                <span
                                    className={combineClassNames(
                                        item.iconColor,
                                        'inline-flex h-10 w-10 items-center justify-center rounded-lg'
                                    )}>
                                    <item.icon
                                        className="h-6 w-6 text-white"
                                        aria-hidden="true"
                                    />
                                </span>
                            </div>
                            <div className="min-w-0 flex-1">
                                <div className="text-sm font-medium text-gray-900">
                                    <span
                                        className="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    {item.name}
                                </div>
                                <p className="text-sm text-gray-500">
                                    {item.description}
                                </p>
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
