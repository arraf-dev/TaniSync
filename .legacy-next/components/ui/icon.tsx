import { cn } from "@/lib/utils";

interface IconProps {
  name: string;
  className?: string;
  filled?: boolean;
}

export function Icon({ name, className, filled = false }: IconProps) {
  return (
    <span
      className={cn("material-symbols-outlined", className)}
      style={filled ? { fontVariationSettings: '"FILL" 1, "wght" 600, "GRAD" 0, "opsz" 24' } : undefined}
      aria-hidden="true"
    >
      {name}
    </span>
  );
}
