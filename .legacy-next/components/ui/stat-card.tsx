import type { DashboardMetric } from "@/lib/types";
import { Icon } from "@/components/ui/icon";
import { cn } from "@/lib/utils";

const toneMap = {
  primary: "bg-primary/10 text-primary",
  accent: "bg-accent-soft text-accent",
  success: "bg-primary-soft text-success",
  warning: "bg-yellow-100 text-warning"
};

export function StatCard({ label, value, detail, icon, tone = "primary" }: DashboardMetric) {
  return (
    <div className="surface-panel h-full p-6">
      <div className={cn("mb-5 flex h-12 w-12 items-center justify-center rounded-2xl", toneMap[tone])}>
        <Icon name={icon} className="text-2xl" filled />
      </div>
      <p className="text-sm font-semibold text-muted">{label}</p>
      <p className="mt-2 font-heading text-3xl font-extrabold text-ink">{value}</p>
      <p className="mt-2 text-sm text-muted">{detail}</p>
    </div>
  );
}
