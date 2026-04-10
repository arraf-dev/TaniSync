import { AppImage } from "@/components/ui/app-image";
import { Icon } from "@/components/ui/icon";
import type { Role, User } from "@/lib/types";

interface TopbarProps {
  user: User;
  role: Role;
}

export function Topbar({ user, role }: TopbarProps) {
  return (
    <header className="glass-panel sticky top-0 z-30 flex h-20 items-center justify-between border-b border-line/70 px-5 md:px-8">
      <div>
        <p className="text-xs font-bold uppercase tracking-[0.2em] text-accent">{role === "admin" ? "Area admin" : "Area petani"}</p>
        <h1 className="mt-1 font-heading text-xl font-extrabold text-ink">{role === "admin" ? "Dashboard Operasional Desa" : "Pusat Catatan Panen"}</h1>
      </div>
      <div className="flex items-center gap-3">
        <button className="hidden rounded-full bg-surface-soft p-3 text-muted transition hover:text-ink md:inline-flex">
          <Icon name="notifications" className="text-xl" />
        </button>
        <button className="hidden rounded-full bg-surface-soft p-3 text-muted transition hover:text-ink md:inline-flex">
          <Icon name="settings" className="text-xl" />
        </button>
        <div className="flex items-center gap-3 rounded-full border border-line/70 bg-white px-3 py-2">
          <AppImage src={user.avatar} alt={user.name} width={40} height={40} roundedClassName="rounded-full" className="h-10 w-10 rounded-full" />
          <div className="hidden sm:block">
            <p className="font-heading text-sm font-bold text-ink">{user.name}</p>
            <p className="text-xs text-muted">{user.village}</p>
          </div>
        </div>
      </div>
    </header>
  );
}
