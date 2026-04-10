import type { ReactNode } from "react";
import { AppShell } from "@/components/layout/app-shell";
import { taniSyncRepository } from "@/lib/api/services";
import { farmerNavigation } from "@/lib/navigation";

export default async function FarmerLayout({ children }: { children: ReactNode }) {
  const user = await taniSyncRepository.getCurrentUser("petani");

  return (
    <AppShell items={farmerNavigation} user={user} role="petani" actionLabel="Catat panen" actionHref="/petani/harvests/new">
      {children}
    </AppShell>
  );
}
