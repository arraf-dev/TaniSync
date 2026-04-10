import Link from "next/link";
import type { QuickAction } from "@/lib/types";
import { Icon } from "@/components/ui/icon";
import { cn } from "@/lib/utils";

const toneStyles = {
  primary: "bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white",
  accent: "bg-accent-soft text-accent group-hover:bg-accent group-hover:text-white",
  success: "bg-primary-soft text-success group-hover:bg-success group-hover:text-white",
  neutral: "bg-surface-soft text-muted group-hover:bg-ink group-hover:text-white"
};

export function ActionCard({ title, description, href, icon, tone = "primary" }: QuickAction) {
  return (
    <Link href={href} className="group surface-panel flex items-center gap-4 p-5 transition hover:-translate-y-0.5">
      <div className={cn("flex h-12 w-12 items-center justify-center rounded-2xl transition", toneStyles[tone])}>
        <Icon name={icon} className="text-2xl" filled />
      </div>
      <div className="space-y-1">
        <p className="font-heading text-base font-bold text-ink">{title}</p>
        <p className="text-sm leading-6 text-muted">{description}</p>
      </div>
    </Link>
  );
}
