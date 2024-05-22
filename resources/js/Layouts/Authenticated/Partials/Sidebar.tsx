import { Link } from "@inertiajs/react";
import { Cog6ToothIcon } from "@heroicons/react/24/outline";
import React from "react";
import { NavigationItem } from "@/types/layouts/authenticated/navigation-item.contract";
import { combineClassNames } from "@/utils/combine-class-names.function";

export default function Sidebar({ navigation }: { navigation: Array<NavigationItem> }) {
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
                                {navigation.map((item) => (
                                    <li key={item.name}>
                                        <Link
                                            href={item.href}
                                            className={combineClassNames(
                                                item.current
                                                ? 'bg-gray-800 text-white'
                                                : 'text-gray-400 hover:text-white hover:bg-gray-800',
                                                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
                                            )}
                                        >
                                            <item.icon className="h-6 w-6 shrink-0" aria-hidden="true" />
                                            {item.name}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </li>
                        <li className="mt-auto">
                            <Link
                                href="#"
                                className="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white"
                            >
                                <Cog6ToothIcon className="h-6 w-6 shrink-0" aria-hidden="true" />
                                Settings
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </>
    );
}
