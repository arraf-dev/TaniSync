import type { ReactNode } from "react";
import { AppShell } from "@/components/layout/app-shell";
import { adminNavigation } from "@/lib/navigation";
import { taniSyncRepository } from "@/lib/api/services";

export default async function AdminLayout({ children }: { children: ReactNode }) {
  const user = await taniSyncRepository.getCurrentUser("admin");

  return (
    <AppShell items={adminNavigation} user={user} role="admin" actionLabel="Tambah data baru" actionHref="/admin/commodities">
      {children}
    </AppShell>
  );
}
