"use client";

import { usePathname } from "next/navigation";
import type { ReactNode } from "react";
import { MobileBottomNav } from "@/components/layout/mobile-bottom-nav";
import { Sidebar } from "@/components/layout/sidebar";
import { Topbar } from "@/components/layout/topbar";
import type { NavigationItem, Role, User } from "@/lib/types";

interface AppShellProps {
  children: ReactNode;
  items: NavigationItem[];
  user: User;
  role: Role;
  actionLabel: string;
  actionHref: string;
}

export function AppShell({ children, items, user, role, actionLabel, actionHref }: AppShellProps) {
  const pathname = usePathname();

  return (
    <div className="min-h-screen md:flex">
      <Sidebar items={items} currentPath={pathname} user={user} actionLabel={actionLabel} actionHref={actionHref} />
      <div className="min-w-0 flex-1">
        <Topbar user={user} role={role} />
        <main className="mx-auto max-w-7xl px-5 pb-28 pt-6 md:px-8 md:pb-10">{children}</main>
      </div>
      <MobileBottomNav items={items} currentPath={pathname} />
    </div>
  );
}
