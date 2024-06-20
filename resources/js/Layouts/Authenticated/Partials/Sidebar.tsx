import { Link } from '@inertiajs/react';
import { Cog6ToothIcon } from '@heroicons/react/24/outline';
import React from 'react';
import { NavigationItem } from '@/types/layouts/authenticated/navigation-item.dto';
import { NavigationCategory } from '@/types/layouts/authenticated/navigation-category.dto';
import SidebarNavigationItem from '@/Layouts/Authenticated/Components/SidebarNavigationItem';

export default function Sidebar({
    navigation,
}: {
    navigation: (NavigationItem | NavigationCategory)[];
}) {
    return (
        <>
            {/* Sidebar component, swap this element with another sidebar if you like */}
            <div className="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4">
                <div className="flex h-16 shrink-0 items-center">
                    <img
                        className="h-8 w-auto"
                        src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                        alt="Your Company"
                    />
                </div>
                <nav className="flex flex-1 flex-col">
                    <ul role="list" className="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" className="-mx-2 space-y-1">
                                {navigation.map((navigationItem, index) =>
                                    navigationItem instanceof
                                    NavigationCategory ? (
                                        <li className={index > 0 ? 'pt-7' : ''}>
                                            <div className="text-xs font-semibold leading-6 text-gray-400">
                                                {navigationItem.name}
                                            </div>
                                            <ul
                                                role="list"
                                                className="-mx-2 mt-2 space-y-1">
                                                {navigationItem.items.map(
                                                    categoryItem => (
                                                        <li
                                                            key={
                                                                categoryItem.name
                                                            }>
                                                            <SidebarNavigationItem
                                                                navigationItem={
                                                                    categoryItem
                                                                }
                                                            />
                                                        </li>
                                                    )
                                                )}
                                            </ul>
                                        </li>
                                    ) : (
                                        <li key={navigationItem.name}>
                                            <SidebarNavigationItem
                                                navigationItem={navigationItem}
                                            />
                                        </li>
                                    )
                                )}
                            </ul>
                        </li>
                        <li className="mt-auto">
                            <Link
                                href="#"
                                className="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white">
                                <Cog6ToothIcon
                                    className="h-6 w-6 shrink-0"
                                    aria-hidden="true"
                                />
                                Settings
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </>
    );
}
