import Image, { type ImageProps } from "next/image";
import { cn } from "@/lib/utils";

type AppImageProps = ImageProps & {
  roundedClassName?: string;
};

export function AppImage({ className, roundedClassName, alt, ...props }: AppImageProps) {
  return (
    <div className={cn("relative overflow-hidden", roundedClassName)}>
      <Image {...props} alt={alt} className={cn("object-cover", className)} />
    </div>
  );
}
