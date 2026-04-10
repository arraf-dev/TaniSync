import { AuthForm } from "@/components/ui/auth-form";
import { AppImage } from "@/components/ui/app-image";
import { appAssets } from "@/lib/assets";

export default function RegisterPage() {
  return (
    <main className="min-h-screen px-5 py-8 md:px-10 lg:px-12">
      <div className="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <section className="flex items-center justify-center order-2 lg:order-1">
          <AuthForm mode="register" />
        </section>
        <section className="relative order-1 hidden overflow-hidden rounded-[2.5rem] border border-line/70 bg-surface-soft p-10 shadow-soft lg:flex lg:flex-col lg:justify-between">
          <div className="space-y-3">
            <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">Registrasi awal</p>
            <h2 className="font-heading text-4xl font-extrabold text-ink">Bangun fondasi akun untuk admin desa maupun petani.</h2>
            <p className="text-sm leading-7 text-muted">Form ini sudah memakai struktur data yang nanti cocok dengan autentikasi dan role management di Laravel.</p>
          </div>
          <AppImage src={appAssets.fieldJournal} alt="Area sawah" width={1200} height={900} roundedClassName="rounded-[2rem]" className="h-[520px] w-full rounded-[2rem]" />
        </section>
      </div>
    </main>
  );
}
