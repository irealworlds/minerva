import React, { PropsWithChildren, useMemo, useState } from 'react';
import { BuildingLibraryIcon, HomeIcon } from '@heroicons/react/24/outline';
import Sidebar from '@/Layouts/Authenticated/Partials/Sidebar';
import MobileSidebarOverlay from '@/Layouts/Authenticated/Partials/MobileSidebarOverlay';
import Navbar from '@/Layouts/Authenticated/Partials/Navbar';
import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';
import { Permission } from '@/types/permission.enum';
import { hasPermission } from '@/utils/access-control/has-permission.function';
import { router } from '@inertiajs/react';

export default function Authenticated({
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

  const navigation = [
    { name: 'Dashboard', actionRoute: 'dashboard', icon: HomeIcon },
    {
      name: 'Institutions',
      actionRoute: 'institutions.index',
      icon: BuildingLibraryIcon,
      permissions: [Permission.InstitutionsCreate],
    },
  ]
    .filter(item => {
      if ('permissions' in item) {
        return hasPermission(checkedUser, item.permissions);
      }

      return true;
    })
    .map(item => ({
      ...item,
      href: route(item.actionRoute),
      current: route().current(item.actionRoute),
    }));

  return (
    <>
      <MobileSidebarOverlay
        navigation={navigation}
        isOpen={sidebarOpen}
        onClose={() => {
          setSidebarOpen(false);
        }}
      />
      <div className="lg:flex">
        {/* Static sidebar for desktop */}
        <aside className="hidden lg:sticky lg:inset-y-0 h-screen lg:z-50 lg:flex lg:w-72 lg:flex-col">
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
            <main className="px-4 sm:px-6 lg:px-8">{children}</main>
          </main>
        </div>
      </div>
    </>
  );
}
