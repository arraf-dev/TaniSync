import type { ReactNode } from "react";
import { cn } from "@/lib/utils";

interface PanelCardProps {
  children: ReactNode;
  className?: string;
  muted?: boolean;
}

export function PanelCard({ children, className, muted = false }: PanelCardProps) {
  return <section className={cn(muted ? "surface-muted" : "surface-panel", "p-6 md:p-8", className)}>{children}</section>;
}
