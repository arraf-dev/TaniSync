import { AppImage } from "@/components/ui/app-image";
import { Button } from "@/components/ui/button";
import { Icon } from "@/components/ui/icon";

interface HeroSectionProps {
  imageSrc: string;
}

export function HeroSection({ imageSrc }: HeroSectionProps) {
  return (
    <section className="px-6 pb-16 pt-28 md:px-10 lg:px-12 lg:pb-24 lg:pt-36">
      <div className="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
        <div className="space-y-8">
          <div className="space-y-5">
            <span className="inline-flex rounded-full bg-primary-soft px-4 py-1.5 text-xs font-bold uppercase tracking-[0.22em] text-primary">
              Sistem agritech desa
            </span>
            <h1 className="editorial-heading max-w-3xl font-heading text-5xl font-extrabold leading-[1.02] text-ink md:text-7xl">
              Digitalisasi pencatatan hasil panen dan harga komoditas desa.
            </h1>
            <p className="max-w-xl text-lg leading-8 text-muted">
              TaniSync membantu admin desa dan petani mencatat panen, memperbarui harga harian, dan menyiapkan laporan
              dengan alur yang singkat serta mudah dipahami.
            </p>
          </div>
          <div className="flex flex-wrap gap-4">
            <Button href="/login" className="px-8 py-4 text-base">
              Mulai masuk
              <Icon name="arrow_forward" className="text-xl" />
            </Button>
            <Button href="#fitur" variant="secondary" className="px-8 py-4 text-base">
              Lihat fitur
              <Icon name="south" className="text-xl" />
            </Button>
          </div>
          <div className="grid gap-4 sm:grid-cols-3">
            {[
              ["2 peran", "Admin desa & petani"],
              ["Input manual", "Harga tetap bisa dicatat tanpa API"],
              ["Siap laporan", "Ekspor PDF & Excel di tahap backend"]
            ].map(([title, text]) => (
              <div key={title} className="rounded-[1.5rem] border border-line/70 bg-white/70 p-4">
                <p className="font-heading text-lg font-bold text-ink">{title}</p>
                <p className="mt-1 text-sm leading-6 text-muted">{text}</p>
              </div>
            ))}
          </div>
        </div>
        <div className="relative">
          <div className="absolute inset-6 rounded-[2.5rem] bg-primary/10 blur-3xl" />
          <div className="relative overflow-hidden rounded-[2.5rem] border border-line/50 bg-white p-3 shadow-soft">
            <AppImage
              src={imageSrc}
              alt="Panorama area pertanian"
              width={1200}
              height={900}
              roundedClassName="rounded-[2rem]"
              className="h-[520px] w-full rounded-[2rem]"
            />
            <div className="absolute bottom-8 left-8 right-8 rounded-[1.5rem] border border-white/30 bg-white/88 p-5 shadow-lg backdrop-blur">
              <div className="flex items-start justify-between gap-4">
                <div>
                  <p className="text-xs font-bold uppercase tracking-[0.2em] text-muted">Update hari ini</p>
                  <p className="mt-2 font-heading text-xl font-bold text-ink">Harga cabai diperbarui manual admin</p>
                  <p className="mt-1 text-sm text-muted">Catatan panen terbaru langsung masuk ke rekap desa.</p>
                </div>
                <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white">
                  <Icon name="monitoring" className="text-2xl" filled />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
