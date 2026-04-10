import type { ReactNode } from "react";
import { cn } from "@/lib/utils";

interface SectionHeaderProps {
  eyebrow?: string;
  title: string;
  description?: string;
  align?: "left" | "center";
  actions?: ReactNode;
}

export function SectionHeader({ eyebrow, title, description, align = "left", actions }: SectionHeaderProps) {
  return (
    <div
      className={cn(
        "flex flex-col gap-4 md:flex-row md:items-end md:justify-between",
        align === "center" && "mx-auto max-w-3xl text-center md:items-center md:justify-center"
      )}
    >
      <div className={cn("space-y-3", align === "center" && "mx-auto text-center")}>
        {eyebrow ? <p className="text-xs font-bold uppercase tracking-[0.24em] text-accent">{eyebrow}</p> : null}
        <h2 className="editorial-heading font-heading text-3xl font-extrabold text-ink md:text-5xl">{title}</h2>
        {description ? <p className="max-w-2xl text-base leading-7 text-muted md:text-lg">{description}</p> : null}
      </div>
      {actions ? <div className="shrink-0">{actions}</div> : null}
    </div>
  );
}
