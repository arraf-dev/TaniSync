import type { ReactNode } from "react";
import type { FilterItem } from "@/lib/types";

interface FilterPanelProps {
  items: FilterItem[];
  actions?: ReactNode;
}

export function FilterPanel({ items, actions }: FilterPanelProps) {
  return (
    <div className="surface-panel p-5 md:p-6">
      <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        {items.map((item) => (
          <div key={item.label} className="space-y-2">
            <label className="text-xs font-bold uppercase tracking-[0.16em] text-muted">{item.label}</label>
            {item.content}
          </div>
        ))}
        {actions ? <div className="flex items-end gap-3">{actions}</div> : null}
      </div>
    </div>
  );
}
