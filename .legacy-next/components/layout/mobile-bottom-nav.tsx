import Link from "next/link";
import { Icon } from "@/components/ui/icon";
import type { NavigationItem } from "@/lib/types";
import { cn } from "@/lib/utils";

interface MobileBottomNavProps {
  items: NavigationItem[];
  currentPath: string;
}

export function MobileBottomNav({ items, currentPath }: MobileBottomNavProps) {
  return (
    <nav className="glass-panel fixed bottom-0 left-0 right-0 z-40 flex h-20 items-center justify-around border-t border-line/70 px-4 md:hidden">
      {items.map((item) => {
        const isActive = currentPath === item.href;
        return (
          <Link key={item.href} href={item.href} className={cn("flex flex-col items-center gap-1", isActive ? "text-primary" : "text-muted")}>
            <Icon name={item.icon} className="text-xl" filled={isActive} />
            <span className="text-[10px] font-bold uppercase tracking-[0.18em]">{item.label}</span>
          </Link>
        );
      })}
    </nav>
  );
}
