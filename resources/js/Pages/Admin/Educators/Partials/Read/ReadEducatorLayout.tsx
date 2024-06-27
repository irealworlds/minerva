import { PropsWithChildren, useMemo } from 'react';
import {
    AdjustmentsHorizontalIcon,
    ArrowLeftIcon,
} from '@heroicons/react/24/outline';
import { Link } from '@inertiajs/react';
import { combineClassNames } from '@/utils/combine-class-names.function';

interface ReadEducatorLayoutProps extends PropsWithChildren {
    educator: {
        key: string;
    };
}

export default function ReadEducatorLayout({
    children,
    educator,
}: ReadEducatorLayoutProps) {
    const navigation = useMemo(
        () =>
            [
                {
                    name: 'Overview',
                    icon: AdjustmentsHorizontalIcon,
                    route: 'admin.educators.read.overview',
                    count: undefined,
                },
            ].map(item => ({
                ...item,
                href: route(item.route, {
                    educator: educator.key,
                }),
                current: route().current(item.route),
            })),
        [educator]
    );

    return (
        <div className="grid grid-cols-1 xl:grid-cols-3 2xl:grid-cols-6 gap-x-12 gap-y-4">
            <div className="relative w-full">
                <Link
                    href={route('admin.educators.list')}
                    className="inline-flex items-center gap-x-3 pl-1 text-sm leading-6 font-semibold text-gray-700 hover:text-indigo-600">
                    <ArrowLeftIcon className="size-4" />
                    Back to list
                </Link>

                {/* TODO Find a better way to sticky-position this */}
                <nav
                    className="flex flex-1 flex-col sticky top-20"
                    aria-label="Sidebar">
                    <ul role="list" className="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" className="-mx-2 space-y-1">
                                {navigation.map(item => (
                                    <li key={item.name}>
                                        <Link
                                            href={item.href}
                                            className={combineClassNames(
                                                item.current
                                                    ? 'bg-gray-100 text-indigo-600'
                                                    : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600',
                                                'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6'
                                            )}>
                                            <item.icon
                                                className={combineClassNames(
                                                    item.current
                                                        ? 'text-indigo-600'
                                                        : 'text-gray-400 group-hover:text-indigo-600',
                                                    'h-6 w-6 shrink-0'
                                                )}
                                                aria-hidden="true"
                                            />
                                            {item.name}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <div className="xl:col-span-2 2xl:col-span-5">{children}</div>
        </div>
    );
}
