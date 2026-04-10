import { Button } from "@/components/ui/button";
import { Icon } from "@/components/ui/icon";

interface EmptyStateProps {
  title: string;
  description: string;
  actionLabel?: string;
  actionHref?: string;
}

export function EmptyState({ title, description, actionLabel, actionHref }: EmptyStateProps) {
  return (
    <div className="surface-muted flex flex-col items-center gap-4 px-6 py-10 text-center">
      <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
        <Icon name="inbox" className="text-3xl" filled />
      </div>
      <div className="space-y-2">
        <h3 className="font-heading text-xl font-bold text-ink">{title}</h3>
        <p className="max-w-md text-sm leading-6 text-muted">{description}</p>
      </div>
      {actionLabel && actionHref ? <Button href={actionHref}>{actionLabel}</Button> : null}
    </div>
  );
}
