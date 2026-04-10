import { Button } from "@/components/ui/button";
import { Icon } from "@/components/ui/icon";

interface ExportCardProps {
  title: string;
  description: string;
  icon: string;
}

export function ExportCard({ title, description, icon }: ExportCardProps) {
  return (
    <div className="surface-panel flex items-center gap-4 p-5">
      <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
        <Icon name={icon} className="text-2xl" filled />
      </div>
      <div className="flex-1 space-y-1">
        <p className="font-heading text-base font-bold text-ink">{title}</p>
        <p className="text-sm leading-6 text-muted">{description}</p>
      </div>
      <Button variant="secondary" className="px-4 py-2.5">
        Unduh
      </Button>
    </div>
  );
}
