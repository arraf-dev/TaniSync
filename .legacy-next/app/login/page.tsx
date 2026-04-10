import { AuthForm } from "@/components/ui/auth-form";
import { AppImage } from "@/components/ui/app-image";
import { appAssets } from "@/lib/assets";

export default function LoginPage() {
  return (
    <main className="min-h-screen px-5 py-8 md:px-10 lg:px-12">
      <div className="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl gap-6 lg:grid-cols-[1.05fr_0.95fr]">
        <section className="relative hidden overflow-hidden rounded-[2.5rem] border border-line/70 bg-surface-soft p-10 shadow-soft lg:flex lg:flex-col lg:justify-between">
          <div>
            <p className="font-heading text-3xl font-extrabold text-primary">TaniSync</p>
            <p className="mt-2 max-w-sm text-sm leading-7 text-muted">Pusat pencatatan panen dan harga komoditas desa yang dirancang mobile-first.</p>
          </div>
          <div className="space-y-6">
            <AppImage src={appAssets.authPanel} alt="Lahan pertanian" width={1200} height={900} roundedClassName="rounded-[2rem]" className="h-[420px] w-full rounded-[2rem]" />
            <div className="rounded-[2rem] bg-white/80 p-6">
              <h2 className="font-heading text-3xl font-extrabold text-ink">Masuk ke dashboard yang sesuai dengan peran Anda.</h2>
              <p className="mt-3 text-sm leading-7 text-muted">Admin dapat memantau desa secara agregat, sementara petani fokus pada input panen dan harga terbaru.</p>
            </div>
          </div>
        </section>
        <section className="flex items-center justify-center">
          <AuthForm mode="login" />
        </section>
      </div>
    </main>
  );
}
