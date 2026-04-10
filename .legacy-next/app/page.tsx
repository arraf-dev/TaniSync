import Link from "next/link";
import { HeroSection } from "@/components/ui/hero-section";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { Icon } from "@/components/ui/icon";
import { Button } from "@/components/ui/button";
import { appAssets } from "@/lib/assets";

const featureCards = [
  {
    title: "Pencatatan hasil panen",
    description: "Petani dapat mencatat tanggal, komoditas, jumlah, satuan, lokasi, dan catatan dengan alur yang singkat.",
    icon: "rebase_edit"
  },
  {
    title: "Harga komoditas harian",
    description: "Admin desa memperbarui harga manual sebagai fallback utama, sehingga informasi tetap terstruktur meski tanpa API pihak ketiga.",
    icon: "payments"
  },
  {
    title: "Dashboard desa",
    description: "Ringkasan panen, tren bulanan, dan distribusi komoditas disajikan dalam panel yang mudah dibaca.",
    icon: "analytics"
  },
  {
    title: "Laporan & ekspor",
    description: "Tampilan laporan sudah disiapkan untuk alur ekspor PDF dan Excel ketika backend Laravel dihubungkan.",
    icon: "description"
  }
];

export default function HomePage() {
  return (
    <div className="min-h-screen">
      <header className="glass-panel fixed inset-x-0 top-0 z-40 border-b border-line/70">
        <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 md:px-10">
          <Link href="/" className="font-heading text-2xl font-extrabold text-primary">
            TaniSync
          </Link>
          <nav className="hidden items-center gap-8 md:flex">
            <Link href="#fitur" className="text-sm font-semibold text-muted transition hover:text-primary">
              Fitur
            </Link>
            <Link href="#manfaat" className="text-sm font-semibold text-muted transition hover:text-primary">
              Manfaat
            </Link>
            <Link href="#alur" className="text-sm font-semibold text-muted transition hover:text-primary">
              Alur
            </Link>
          </nav>
          <div className="flex items-center gap-3">
            <Button href="/login" variant="secondary" className="hidden md:inline-flex">
              Login
            </Button>
            <Button href="/register">Daftar</Button>
          </div>
        </div>
      </header>

      <main>
        <HeroSection imageSrc={appAssets.landingHero} />

        <section id="fitur" className="px-6 py-20 md:px-10 lg:px-12">
          <div className="mx-auto max-w-7xl space-y-12">
            <SectionHeader
              eyebrow="Fitur utama"
              title="Rangkaian screen yang mengikuti referensi Anda, lalu dirapikan jadi sistem yang konsisten."
              description="Semua halaman dibangun dengan token desain yang sama agar admin dan petani merasakan alur yang rapi, jelas, dan tetap ringan dipakai di ponsel."
              align="center"
            />
            <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
              {featureCards.map((feature) => (
                <PanelCard key={feature.title} muted className="h-full">
                  <div className="space-y-5">
                    <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                      <Icon name={feature.icon} className="text-3xl" filled />
                    </div>
                    <div className="space-y-3">
                      <h3 className="font-heading text-2xl font-extrabold text-ink">{feature.title}</h3>
                      <p className="text-sm leading-7 text-muted">{feature.description}</p>
                    </div>
                  </div>
                </PanelCard>
              ))}
            </div>
          </div>
        </section>

        <section id="manfaat" className="bg-white px-6 py-20 md:px-10 lg:px-12">
          <div className="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.95fr_1.05fr]">
            <PanelCard muted className="bg-[radial-gradient(circle_at_top_right,rgba(25,107,44,0.12),transparent_40%),#f1f4ee]">
              <div className="space-y-6">
                <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">Kenapa TaniSync</p>
                <h2 className="editorial-heading font-heading text-4xl font-extrabold text-ink">
                  Dirancang untuk kerja lapangan yang cepat, bukan untuk membuat pengguna berputar-putar.
                </h2>
                <div className="space-y-5">
                  {[
                    "Form panen singkat dan mobile-first untuk petani.",
                    "Harga harian tersimpan terstruktur dan mudah dicek ulang.",
                    "Dashboard admin fokus pada ringkasan desa, bukan metrik yang tidak relevan.",
                    "Siap dihubungkan ke Laravel tanpa ganti struktur tampilan."
                  ].map((item) => (
                    <div key={item} className="flex gap-3">
                      <div className="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-white">
                        <Icon name="check" className="text-lg" filled />
                      </div>
                      <p className="text-sm leading-7 text-muted">{item}</p>
                    </div>
                  ))}
                </div>
              </div>
            </PanelCard>
            <div className="grid gap-6 sm:grid-cols-2">
              {[
                ["Transparansi data", "Admin dan petani mengakses data dari sumber yang sama sehingga laporan lebih konsisten."],
                ["Siap untuk desa", "Bahasa antarmuka Indonesia, label jelas, dan struktur navigasi sederhana."],
                ["Fallback manual", "Harga komoditas tetap berjalan walau integrasi pihak ketiga belum ada."],
                ["Ekspor terarah", "Frontend sudah menyiapkan alur untuk PDF dan Excel sesuai PRD."]
              ].map(([title, description]) => (
                <PanelCard key={title} className="h-full">
                  <h3 className="font-heading text-2xl font-extrabold text-ink">{title}</h3>
                  <p className="mt-3 text-sm leading-7 text-muted">{description}</p>
                </PanelCard>
              ))}
            </div>
          </div>
        </section>

        <section id="alur" className="px-6 py-20 md:px-10 lg:px-12">
          <div className="mx-auto max-w-7xl">
            <SectionHeader
              eyebrow="Alur utama"
              title="Alur petani dan admin dibuat sejajar dengan PRD."
              description="Rute publik, halaman per peran, dan service layer mock sudah dirangkai untuk memudahkan integrasi backend berikutnya."
            />
            <div className="mt-10 grid gap-6 lg:grid-cols-2">
              <PanelCard>
                <p className="text-xs font-bold uppercase tracking-[0.22em] text-primary">Petani</p>
                <div className="mt-6 space-y-4">
                  {["Login ke aplikasi.", "Catat panen dengan form singkat.", "Lihat riwayat panen pribadi.", "Bandingkan harga komoditas terbaru."].map((step, index) => (
                    <div key={step} className="flex gap-4">
                      <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary-soft font-heading font-bold text-primary">
                        {index + 1}
                      </div>
                      <p className="pt-2 text-sm leading-7 text-muted">{step}</p>
                    </div>
                  ))}
                </div>
              </PanelCard>
              <PanelCard>
                <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">Admin desa</p>
                <div className="mt-6 space-y-4">
                  {[
                    "Masuk ke dashboard operasional desa.",
                    "Kelola master komoditas dan harga harian.",
                    "Pantau log panen yang masuk dari petani.",
                    "Buka laporan dan siapkan ekspor."
                  ].map((step, index) => (
                    <div key={step} className="flex gap-4">
                      <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-accent-soft font-heading font-bold text-accent">
                        {index + 1}
                      </div>
                      <p className="pt-2 text-sm leading-7 text-muted">{step}</p>
                    </div>
                  ))}
                </div>
              </PanelCard>
            </div>
          </div>
        </section>

        <section className="px-6 pb-20 md:px-10 lg:px-12">
          <div className="mx-auto max-w-7xl rounded-[2.5rem] bg-primary p-10 text-center text-white shadow-panel md:p-16">
            <p className="text-xs font-bold uppercase tracking-[0.22em] text-primary-soft">Siap dipakai</p>
            <h2 className="mt-4 font-heading text-4xl font-extrabold md:text-5xl">Masuk ke alur frontend TaniSync sekarang.</h2>
            <p className="mx-auto mt-4 max-w-2xl text-base leading-8 text-primary-soft">
              Landing page publik, auth, area petani, dan area admin sudah dirancang sebagai fondasi yang siap dikembangkan bersama backend Laravel.
            </p>
            <div className="mt-8 flex flex-wrap justify-center gap-4">
              <Button href="/login" variant="secondary" className="bg-white text-primary hover:bg-surface-soft">
                Coba login
              </Button>
              <Button href="/register" className="border border-white/20 bg-white/10 shadow-none hover:bg-white/20">
                Buat akun
              </Button>
            </div>
          </div>
        </section>
      </main>

      <footer className="border-t border-line/70 bg-white px-6 py-10 md:px-10 lg:px-12">
        <div className="mx-auto flex max-w-7xl flex-col gap-4 text-sm text-muted md:flex-row md:items-center md:justify-between">
          <p>© 2026 TaniSync. Frontend MVP untuk pencatatan panen dan harga komoditas desa.</p>
          <div className="flex gap-6">
            <Link href="/login" className="font-semibold text-primary">
              Login
            </Link>
            <Link href="/register" className="font-semibold text-primary">
              Register
            </Link>
          </div>
        </div>
      </footer>
    </div>
  );
}
