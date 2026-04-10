import { HarvestEntryForm } from "@/components/ui/harvest-entry-form";
import { AppImage } from "@/components/ui/app-image";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { appAssets } from "@/lib/assets";
import { taniSyncRepository } from "@/lib/api/services";

export default async function FarmerNewHarvestPage() {
  const commodities = await taniSyncRepository.getCommodities();

  return (
    <div className="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
      <PanelCard muted className="relative overflow-hidden">
        <div className="space-y-4">
          <p className="text-xs font-bold uppercase tracking-[0.2em] text-accent">Jurnal panen</p>
          <h2 className="editorial-heading font-heading text-4xl font-extrabold text-ink">Catat hasil panen dengan langkah yang singkat.</h2>
          <p className="text-sm leading-7 text-muted">Form ini mengikuti PRD: tanggal, komoditas, jumlah, satuan, dan catatan tambahan. Saya tambahkan lokasi dan kualitas agar lebih berguna saat validasi admin.</p>
        </div>
        <AppImage src={appAssets.fieldJournal} alt="Lahan pertanian" width={1200} height={1000} roundedClassName="mt-8 rounded-[2rem]" className="h-[360px] w-full rounded-[2rem]" />
      </PanelCard>

      <div className="space-y-6">
        <SectionHeader
          eyebrow="Input panen"
          title="Rekam panen musim ini"
          description="Isi data utama terlebih dahulu. Sisanya bisa dilengkapi admin saat proses validasi."
        />
        <HarvestEntryForm commodities={commodities.filter((commodity) => commodity.status === "aktif")} />
      </div>
    </div>
  );
}
