import { NavigationItem } from '@/types/layouts/authenticated/navigation-item.dto';
import { combineClassNames } from '@/utils/combine-class-names.function';
import { Link } from '@inertiajs/react';
import React from 'react';

interface SidebarNavigationItemProps {
    navigationItem: NavigationItem;
}

export default function SidebarNavigationItem({
    navigationItem,
}: SidebarNavigationItemProps) {
    return (
        <Link
            href={navigationItem.href}
            className={combineClassNames(
                navigationItem.current && !navigationItem.disabled
                    ? 'bg-gray-800 text-white'
                    : 'text-gray-400 hover:text-white hover:bg-gray-800',
                navigationItem.disabled
                    ? 'opacity-70 cursor-not-allowed pointer-events-none select-none'
                    : '',
                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
            )}>
            <navigationItem.icon
                className="h-6 w-6 shrink-0"
                aria-hidden="true"
            />
            {navigationItem.name}
        </Link>
    );
}
