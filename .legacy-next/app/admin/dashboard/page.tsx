import { ActionCard } from "@/components/ui/action-card";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { StatCard } from "@/components/ui/stat-card";
import { taniSyncRepository } from "@/lib/api/services";

export default async function AdminDashboardPage() {
  const dashboard = await taniSyncRepository.getAdminDashboard();

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Dashboard admin"
        title="Ringkasan operasional desa"
        description="Pantau produktivitas desa, cek distribusi panen, dan akses tindakan utama dari satu halaman yang lebih rapi."
      />

      <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        {dashboard.metrics.map((metric) => (
          <StatCard key={metric.label} {...metric} />
        ))}
      </div>

      <div className="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
        <PanelCard className="space-y-8">
          <div className="flex items-center justify-between gap-4">
            <div>
              <h3 className="font-heading text-2xl font-extrabold text-ink">Tren panen bulanan</h3>
              <p className="mt-2 text-sm leading-6 text-muted">Visual sederhana untuk melihat perubahan volume panen dari bulan ke bulan.</p>
            </div>
            <span className="rounded-full bg-primary-soft px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-primary">6 bulan</span>
          </div>
          <div className="flex h-72 items-end gap-4">
            {dashboard.trends.map((point) => (
              <div key={point.label} className="flex flex-1 flex-col items-center gap-3">
                <div className="flex h-full w-full items-end rounded-[1.5rem] bg-surface-soft p-2">
                  <div className="w-full rounded-[1.1rem] bg-primary/80" style={{ height: `${point.value}%` }} />
                </div>
                <div className="text-center">
                  <p className="text-sm font-bold text-ink">{point.label}</p>
                  <p className="text-xs text-muted">{point.value}% target</p>
                </div>
              </div>
            ))}
          </div>
        </PanelCard>

        <PanelCard muted className="space-y-6">
          <div>
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent">Distribusi komoditas</p>
            <h3 className="mt-2 font-heading text-2xl font-extrabold text-ink">Kontributor panen terbesar</h3>
          </div>
          <div className="space-y-4">
            {dashboard.harvestDistribution.map((item) => (
              <div key={item.label} className="space-y-2">
                <div className="flex items-center justify-between text-sm">
                  <span className="font-semibold text-ink">{item.label}</span>
                  <span className="font-bold text-primary">{item.value}%</span>
                </div>
                <div className="h-3 rounded-full bg-white">
                  <div className="h-3 rounded-full bg-primary" style={{ width: `${item.value}%` }} />
                </div>
              </div>
            ))}
          </div>
          <div className="rounded-[1.5rem] bg-primary p-5 text-white">
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-primary-soft">Catatan admin</p>
            <p className="mt-2 text-sm leading-7">Gunakan panel harga harian dan validasi panen untuk menjaga laporan desa tetap konsisten setiap minggu.</p>
          </div>
        </PanelCard>
      </div>

      <div className="grid gap-4 lg:grid-cols-3">
        {dashboard.quickActions.map((action) => (
          <ActionCard key={action.title} {...action} />
        ))}
      </div>
    </div>
  );
}
