import React, {
    createContext,
    PropsWithChildren,
    useMemo,
    useState,
} from 'react';
import {
    BuildingLibraryIcon,
    ChartBarSquareIcon,
    ClipboardDocumentListIcon,
    IdentificationIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';
import Sidebar from '@/Layouts/Authenticated/Partials/Sidebar';
import MobileSidebarOverlay from '@/Layouts/Authenticated/Partials/MobileSidebarOverlay';
import Navbar from '@/Layouts/Authenticated/Partials/Navbar';
import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';
import { Permission } from '@/types/permission.enum';
import { checkPermissionsForUser } from '@/utils/access-control/has-permission.function';
import { router } from '@inertiajs/react';
import Root from '@/Root';
import { NavigationCategory } from '@/types/layouts/authenticated/navigation-category.dto';
import { NavigationItem } from '@/types/layouts/authenticated/navigation-item.dto';

export const AuthenticatedContext = createContext<{
    user: AuthenticatedUserViewModel;
    hasPermissions: (
        ...permissionSets: Permission[][] | Permission[]
    ) => boolean;
}>({
    user: undefined as unknown as AuthenticatedUserViewModel,
    hasPermissions: () => false,
});

export default function AuthenticatedLayout({
    children,
    user,
}: PropsWithChildren<{ user: AuthenticatedUserViewModel | null }>) {
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const checkedUser = useMemo<AuthenticatedUserViewModel>(() => {
        if (user === null) {
            router.visit(route('login'));
            throw new Error('Unauthenticated');
        }

        return user;
    }, [user]);

    const navigation: (NavigationItem | NavigationCategory)[] = [
        // Admin routes
        new NavigationCategory('Administration', [
            {
                name: 'Administration dashboard',
                href: route('dashboard'),
                current: route().current('dashboard'),
                icon: ChartBarSquareIcon,
            },
            {
                name: 'Institutions',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: BuildingLibraryIcon,
                permissions: [Permission.InstitutionsCreate],
            },
        ]),

        // Educator routes
        new NavigationCategory('Educator area', [
            {
                name: 'Educator dashboard',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: ChartBarSquareIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
            {
                name: 'Student groups',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: UserGroupIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
            {
                name: 'Grades',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: ClipboardDocumentListIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
        ]),

        // Student routes
        new NavigationCategory('Student area', [
            {
                name: 'Student dashboard',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: ChartBarSquareIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
            {
                name: 'Student ID',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: IdentificationIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
            {
                name: 'My enrolments',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: UserGroupIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
            {
                name: 'My grades',
                href: route('admin.institutions.index'),
                current: route().current('admin.institutions.index'),
                icon: ClipboardDocumentListIcon,
                permissions: [Permission.InstitutionsCreate],
                disabled: true,
            },
        ]),
    ].filter((item: NavigationItem | NavigationCategory) => {
        if (item instanceof NavigationCategory) {
            item.items = item.items.filter(i => {
                if (!i.permissions?.length) {
                    return true;
                } else {
                    return checkPermissionsForUser(
                        checkedUser,
                        i.permissions ?? []
                    );
                }
            });
            return item.items.length > 0;
        } else {
            if (!item.permissions?.length) {
                return true;
            } else {
                return checkPermissionsForUser(
                    checkedUser,
                    item.permissions ?? []
                );
            }
        }
    });

    return (
        <Root>
            <AuthenticatedContext.Provider
                value={{
                    user: checkedUser,
                    hasPermissions: (...permissions) =>
                        checkPermissionsForUser(checkedUser, ...permissions),
                }}>
                <MobileSidebarOverlay
                    navigation={navigation}
                    isOpen={sidebarOpen}
                    onClose={() => {
                        setSidebarOpen(false);
                    }}
                />
                <div className="lg:flex">
                    {/* Static sidebar for desktop */}
                    <aside className="hidden lg:sticky lg:inset-y-0 h-screen lg:z-50 lg:flex lg:w-72 lg:flex-col lg:shrink-0">
                        <Sidebar navigation={navigation} />
                    </aside>

                    <div className="flex flex-col grow bg-gray-50 min-h-screen">
                        <Navbar
                            user={checkedUser}
                            onSidebarOpen={() => {
                                setSidebarOpen(true);
                            }}
                        />

                        <main className="py-10 grow">
                            <main className="px-4 sm:px-6 lg:px-8">
                                {children}
                            </main>
                        </main>
                    </div>
                </div>
            </AuthenticatedContext.Provider>
        </Root>
    );
}
