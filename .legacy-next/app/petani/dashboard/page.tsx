import { ActionCard } from "@/components/ui/action-card";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { StatCard } from "@/components/ui/stat-card";
import { formatCurrency } from "@/lib/utils";
import { taniSyncRepository } from "@/lib/api/services";

export default async function FarmerDashboardPage() {
  const dashboard = await taniSyncRepository.getFarmerDashboard();

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Dashboard petani"
        title="Ringkasan panen dan harga terbaru"
        description="Halaman utama petani menjaga alur tetap ringkas: lihat progress panen, cek harga, lalu lanjutkan ke form catat panen."
      />

      <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        {dashboard.metrics.map((metric) => (
          <StatCard key={metric.label} {...metric} />
        ))}
      </div>

      <div className="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <PanelCard className="space-y-8">
          <div className="flex items-center justify-between gap-4">
            <div>
              <h3 className="font-heading text-2xl font-extrabold text-ink">Grafik hasil panen</h3>
              <p className="mt-2 text-sm leading-6 text-muted">Visual sederhana untuk membantu petani melihat perkembangan hasil dari waktu ke waktu.</p>
            </div>
            <span className="rounded-full bg-primary-soft px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-primary">6 bulan</span>
          </div>
          <div className="flex h-72 items-end gap-4">
            {dashboard.trends.map((point) => (
              <div key={point.label} className="flex flex-1 flex-col items-center gap-3">
                <div className="flex h-full w-full items-end rounded-[1.5rem] bg-surface-soft p-2">
                  <div className="w-full rounded-[1.1rem] bg-primary/85" style={{ height: `${point.value}%` }} />
                </div>
                <div className="text-center">
                  <p className="text-sm font-bold text-ink">{point.label}</p>
                  <p className="text-xs text-muted">{point.value}%</p>
                </div>
              </div>
            ))}
          </div>
        </PanelCard>

        <PanelCard muted className="space-y-6">
          <div>
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent">Harga terkini</p>
            <h3 className="mt-2 font-heading text-2xl font-extrabold text-ink">Referensi cepat sebelum menjual panen</h3>
          </div>
          <div className="space-y-4">
            {dashboard.latestPrices.map((price) => (
              <div key={price.id} className="rounded-[1.5rem] bg-white p-4">
                <div className="flex items-center justify-between gap-4">
                  <div>
                    <p className="font-heading text-lg font-bold text-ink">{price.commodityName}</p>
                    <p className="text-xs text-muted">{price.sourceNote}</p>
                  </div>
                  <div className="text-right">
                    <p className="font-heading text-xl font-extrabold text-primary">{formatCurrency(price.price)}</p>
                    <p className="text-xs text-muted">{price.effectiveDate}</p>
                  </div>
                </div>
              </div>
            ))}
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
