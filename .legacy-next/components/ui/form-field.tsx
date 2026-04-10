import type { InputHTMLAttributes, ReactNode, SelectHTMLAttributes, TextareaHTMLAttributes } from "react";
import { cn } from "@/lib/utils";

interface BaseFieldProps {
  label?: string;
  helper?: string;
  icon?: ReactNode;
}

export function InputField({
  label,
  helper,
  icon,
  className,
  ...props
}: BaseFieldProps & InputHTMLAttributes<HTMLInputElement>) {
  return (
    <label className="block space-y-2">
      {label ? <span className="text-sm font-semibold text-muted">{label}</span> : null}
      <span className="relative block">
        {icon ? <span className="absolute left-4 top-1/2 -translate-y-1/2 text-muted">{icon}</span> : null}
        <input
          className={cn(
            "w-full rounded-2xl border border-line bg-surface px-4 py-3.5 text-sm text-ink outline-none transition placeholder:text-muted/70 focus:border-primary focus:ring-4 focus:ring-primary/10",
            Boolean(icon) && "pl-12",
            className
          )}
          {...props}
        />
      </span>
      {helper ? <span className="text-xs text-muted">{helper}</span> : null}
    </label>
  );
}

export function SelectField({
  label,
  helper,
  className,
  children,
  ...props
}: BaseFieldProps &
  SelectHTMLAttributes<HTMLSelectElement> & {
    children: ReactNode;
  }) {
  return (
    <label className="block space-y-2">
      {label ? <span className="text-sm font-semibold text-muted">{label}</span> : null}
      <select
        className={cn(
          "w-full rounded-2xl border border-line bg-surface px-4 py-3.5 text-sm text-ink outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10",
          className
        )}
        {...props}
      >
        {children}
      </select>
      {helper ? <span className="text-xs text-muted">{helper}</span> : null}
    </label>
  );
}

export function TextareaField({
  label,
  helper,
  className,
  ...props
}: BaseFieldProps & TextareaHTMLAttributes<HTMLTextAreaElement>) {
  return (
    <label className="block space-y-2">
      {label ? <span className="text-sm font-semibold text-muted">{label}</span> : null}
      <textarea
        className={cn(
          "w-full rounded-2xl border border-line bg-surface px-4 py-3.5 text-sm text-ink outline-none transition placeholder:text-muted/70 focus:border-primary focus:ring-4 focus:ring-primary/10",
          className
        )}
        {...props}
      />
      {helper ? <span className="text-xs text-muted">{helper}</span> : null}
    </label>
  );
}
