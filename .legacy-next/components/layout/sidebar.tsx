import Link from "next/link";
import { Icon } from "@/components/ui/icon";
import { AppImage } from "@/components/ui/app-image";
import type { NavigationItem, User } from "@/lib/types";
import { cn } from "@/lib/utils";

interface SidebarProps {
  items: NavigationItem[];
  currentPath: string;
  user: User;
  actionLabel: string;
  actionHref: string;
}

export function Sidebar({ items, currentPath, user, actionLabel, actionHref }: SidebarProps) {
  return (
    <aside className="hidden h-screen w-72 shrink-0 flex-col border-r border-line/70 bg-white/80 px-5 py-8 md:flex">
      <div className="px-3">
        <p className="font-heading text-2xl font-extrabold text-primary">TaniSync</p>
        <p className="mt-1 text-[11px] font-bold uppercase tracking-[0.22em] text-muted">Digital agritech desa</p>
      </div>
      <nav className="mt-10 flex flex-1 flex-col gap-2">
        {items.map((item) => {
          const isActive = currentPath === item.href;
          return (
            <Link
              key={item.href}
              href={item.href}
              className={cn(
                "flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition",
                isActive ? "bg-primary-soft text-primary" : "text-muted hover:bg-surface-soft hover:text-ink"
              )}
            >
              <Icon name={item.icon} className="text-xl" filled={isActive} />
              <span>{item.label}</span>
            </Link>
          );
        })}
      </nav>
      <Link
        href={actionHref}
        className="mt-6 inline-flex items-center justify-center gap-2 rounded-2xl bg-primary px-5 py-4 text-sm font-bold text-white shadow-panel transition hover:bg-primary-strong"
      >
        <Icon name="add_circle" className="text-xl" filled />
        {actionLabel}
      </Link>
      <div className="mt-6 flex items-center gap-3 border-t border-line/70 px-3 pt-6">
        <AppImage src={user.avatar} alt={user.name} width={48} height={48} roundedClassName="rounded-2xl" className="h-12 w-12 rounded-2xl" />
        <div className="min-w-0">
          <p className="truncate font-heading text-sm font-bold text-ink">{user.name}</p>
          <p className="truncate text-xs text-muted">{user.email}</p>
        </div>
      </div>
    </aside>
  );
}
