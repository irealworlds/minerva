import React, { Fragment, PropsWithChildren, useState } from 'react';
import { HomeIcon } from '@heroicons/react/24/outline'
import Sidebar from "@/Layouts/Authenticated/Partials/Sidebar";
import MobileSidebarOverlay from "@/Layouts/Authenticated/Partials/MobileSidebarOverlay";
import { User } from "@/types";
import Navbar from "@/Layouts/Authenticated/Partials/Navbar";


export default function Authenticated({ children, user }: PropsWithChildren<{ user: User }>) {
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const navigation = [
        { name: 'Dashboard', href: route('dashboard'), icon: HomeIcon, current: route().current('dashboard') },
    ];

    return (
        <>
            <MobileSidebarOverlay navigation={navigation} isOpen={sidebarOpen}
                                  onClose={() => setSidebarOpen(false)} />
            <div className="lg:flex">
                {/* Static sidebar for desktop */}
                <aside className="hidden lg:sticky lg:inset-y-0 h-screen lg:z-50 lg:flex lg:w-72 lg:flex-col">
                    <Sidebar navigation={navigation} />
                </aside>

                <div className="flex flex-col grow bg-gray-50 min-h-screen">
                    <Navbar user={user} onSidebarOpen={() => setSidebarOpen(true)} />

                    <main className="py-10 grow">
                        <main className="px-4 sm:px-6 lg:px-8">{children}</main>
                    </main>
                </div>
            </div>
        </>
    );
}
